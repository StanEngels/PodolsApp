<?php

declare(strict_types=1);

namespace DAL;

use interfaces\IDALPreset;
use interfaces\Idb;

class DALPreset implements IDALPreset
{
    private array $data;
    private array $effects;
    private array $presets;

    private IDb $Idb;

    public function __construct()
    {
        $this->Idb = new db("localhost", "podols", "root", "");
        $this->data = [];
        $this->effects = [];
        $this->presets = [];
    }

    private function validateUser(string $userID, string $presetID): bool
    {
        $sql = "SELECT * FROM join_account_preset WHERE account_id='" . $userID . "' AND preset_id='" . $presetID . "';";
        if ($this->Idb->selectQuery($sql)) {
            return true;
        } else {
            return false;
        }
    }

    private function getPreset(string $presetID): void
    {
        $sql = "SELECT `name` FROM preset WHERE id='" . $presetID . "';";
        foreach ($this->Idb->selectQuery($sql) as $row) {
            $this->data['preset'] = $row->name;
        }
    }

    private function getEffects(string $presetID): void
    {
        $sql = "SELECT `type`, `settings`
                    FROM preset
                    JOIN join_effect_preset AS JEP ON JEP.preset_id=preset.id
                    JOIN effect ON JEP.effect_id=effect.id
                    WHERE preset.id='" . $presetID . "';";
        foreach ($this->Idb->selectQuery($sql) as $row) {
            $this->effects[] = $row->type;
        }
        if (!empty($this->effects)) {
            $this->data['effects'] = $this->effects;
        }
    }

    public function loadPreset(string $userID, string $presetID): array
    {
        if ($this->validateUser($userID, $presetID)) {
            $this->getPreset($presetID);
            $this->getEffects($presetID);
        }
        return $this->data;
    }

    public function createPresetList(string $userID): array
    {
        $sql = "SELECT preset.id as preset_id, preset.name AS preset_name
                    FROM account
                    JOIN join_account_preset AS JAP ON JAP.account_id=account.id
                    JOIN preset ON JAP.preset_id=preset.id
                    WHERE account.id='" . $userID . "';";
        foreach ($this->Idb->selectQuery($sql) as $row) {
            $this->presets[] = $row;
        }
        return $this->presets;
    }

    public function getPresetByName(string $presetName): array
    {
        $currentPresetIdQuery = "SELECT id FROM preset WHERE name='" . $presetName . "';";
        return $this->Idb->selectQuery($currentPresetIdQuery);
    }

    public function addPreset(string $presetName, string $isPublic, string $userID): void
    {
        $sql = "INSERT INTO preset (name, isPublic, creator)VALUES('" . $presetName . "','" . $isPublic . "', '" . $userID . "');";
        $this->Idb->actionQuery($sql);
        foreach ($this->getPresetByName($presetName) as $row) {
            $sql = "INSERT INTO join_account_preset (account_id, preset_id)VALUES ('" . $userID . "','" . $row->id . "');";
            $this->Idb->actionQuery($sql);
        }
    }

    public function deletePreset(string $presetId): void
    {
        $sql = "DELETE FROM preset WHERE id='" . $presetId . "';";
        $this->Idb->actionQuery($sql);
    }

    public function editPresetName(string $presetId, string $editPresetName): void
    {
        $sql = "UPDATE preset SET name = '" . $editPresetName . "' WHERE id='" . $presetId . "';";
        $this->Idb->actionQuery($sql);
    }

    public function addEffectToPreset(string $presetId, string $idEffect): void
    {
        $sql = "INSERT INTO join_effect_preset (preset_id, effect_id)VALUES('" . $presetId . "', '" . $idEffect . "');";
        $this->Idb->actionQuery($sql);
    }
}