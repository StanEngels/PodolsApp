<?php

namespace interfaces;

interface IDALPreset
{
    public function loadPreset(string $userID, string $presetID);

    public function createPresetList(string $userID);

    public function addPreset(string $presetName, string $isPublic, string $userID);

    public function deletePreset(string $presetName);

    public function editPresetName(string $presetId, string $editPresetName);

    public function getPresetByName(string $presetName);

    public function addEffectToPreset(string $presetId, string $idEffect);
}
