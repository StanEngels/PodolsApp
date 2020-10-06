<?php


namespace functional;

use interfaces\IDALPreset;
use interfaces\IPresetMethods;

class presetMethods extends preset implements IPresetMethods
{
    public IDALPreset $IDALPreset;

    public function __construct(IDALPreset $PresetDAL)
    {
        $this->IDALPreset = $PresetDAL;
    }

    public function getPresetData(string $accountId, string $presetId): array
    {
        try {
            return $presetData = $this->IDALPreset->loadPreset($accountId, $presetId);
        } catch (\Error $e) {
            $this->error["getPresetData"] = "You do not have this preset";
        }
        return $this->error["getPresetData"];
    }

    public function getPresetList(string $userID): array
    {
        try {
            $presetList = $this->IDALPreset->createPresetList($userID);
            if (!empty($presetList)) {
                return $presetList;
            } else {
                $this->error["getPresetList"] = "You don't have any presets, lets make one!";
            }
        } catch (\Error $e) {
            $this->error["getPresetListFatal"] = "There is something wrong report this to the staff please at: (email)";
        }
        return $this->error;
    }

    public function editPresetInfo(string $presetId, ?string $editPresetName, string $idEffect): array
    {
        try {
            if ($presetId != "") {
                if ($editPresetName == null) {
                    $this->IDALPreset->addEffectToPreset($presetId, $idEffect);
                } elseif ($idEffect == "none") {
                    $this->IDALPreset->editPresetName($presetId, $editPresetName);
                } else {
                    $this->IDALPreset->addEffectToPreset($presetId, $idEffect);
                    $this->IDALPreset->editPresetName($presetId, $editPresetName);
                }
            } else {
                $this->error["editPresetInfo"] = "Please choose a preset to add these values to";
            }
        } catch (\Error $e) {
            $this->error["editPresetInfo"] = "Can not edit the preset at the moment";
        }
        return $this->error;
    }

    public function deletePresetInfo(string $presetId): array
    {
        try {
            $this->IDALPreset->deletePreset($presetId);
        } catch (\Error $e) {
            $this->error["deletePresetInfo"] = "The preset you want to delete doesn't exist";
        }
        return $this->error;
    }
}