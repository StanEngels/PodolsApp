<?php

namespace interfaces;

interface IPresetMethods
{
    public function getPresetData(string $accountId, string $presetId);

    public function getPresetList(string $userID);

    public function deletePresetInfo(string $presetName);

    public function editPresetInfo(string $presetId,string $editPresetName ,string $effect);
}
