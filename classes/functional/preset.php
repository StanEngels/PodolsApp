<?php

declare(strict_types=1);

namespace functional;

use interfaces\IDALPreset;
use interfaces\IPreset;


class preset implements IPreset
{

    public string $name;
    public string $creator;
    public string $isPublic;
    public IDALPreset $IDALPreset;
    public array $error = [];

    public function __construct(string $name, string $creator, string $isPublic, IDALPreset $PresetDAL)
    {
        $this->name = $name;
        $this->creator = $creator;
        $this->isPublic = $isPublic;
        $this->IDALPreset = $PresetDAL;
    }

    public function addPresetInfo(): array
    {
        if ($this->name != "") {
            try {
                $this->IDALPreset->addPreset($this->name, $this->creator, $this->isPublic);
            } catch (\Error $e) {
                $this->error["addPresetInfo"] = "System can't add your preset right now.";
            }
        } else {
            $this->error["addPresetInfo"] = "Preset must have a name!";
        }
        return $this->error;
    }
}
