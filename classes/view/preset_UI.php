<?php

declare(strict_types=1);

namespace view;

use DAL\DALPreset;
use functional\preset;
use functional\presetMethods;
use interfaces\IPreset;
use interfaces\IPresetMethods;

class preset_UI
{

    private IPresetMethods $IPresetMethods;
    private IPreset $IPreset;
    private array $presetList = [];
    private string $error;

    public function __construct()
    {
        $PresetDAL = new DALPreset();
        $this->IPresetMethods = new presetMethods($PresetDAL);
        $this->error = "";
    }

    public function parsePresetData(): void
    {
        if (isset($_GET["preset"])) {
            $this->presetList = $this->IPresetMethods->getPresetData('1', $_GET["preset"]);
            if (!empty($this->presetList["preset"])) {
                echo $this->presetList["preset"];
                if (!empty($this->presetList["effects"])) {
                    foreach ($this->presetList["effects"] as $row) {
                        echo "<div id='" . $row . "'> - " . $row . "</div>";
                    }
                }
            } else {
                echo "This preset doesn't exist.";
            }
        }
    }

    public function parsePresetList(): void
    {
        $result = $this->IPresetMethods->getPresetList('1');
        if (empty($result["getPresetListFatal"])) {
            echo "<button type='submit' id='preButton' name='addPreset' value='newPreset'>New Preset</button>
              <br/>";
            if (empty($result["getPresetList"])) {
                foreach ($result as $row) {
                    echo "<button type='submit' id='preButton' name='preset' value='" . $row->preset_id . "'>" . $row->preset_name . "</button><br/>";
                }
            } else {
                $this->error = $result["getPresetList"];
                echo $this->error;
            }
        } else {
            header('Location: dbError.html');
            die();
        }
    }

    public function addPresetUI(): void
    {
        if (isset($_GET["addPreset"])) {
            echo "<form method=\"post\">
                          <label for=\"preName\">New preset name:</label><br>
                          <input type=\"text\" id=\"preName\" name=\"preName\"><br>
                          <input type=\"submit\" name=\"submitNewPreset\">
                      </form>";

            if (isset($_POST["submitNewPreset"])) {
                $PresetDAL = new DALPreset();
                $preName = htmlspecialchars($_POST['preName'], ENT_QUOTES, 'UTF-8');

                $this->IPreset = new preset($preName, '1', "1", $PresetDAL);
                $result = $this->IPreset->addPresetInfo();
                if (empty($result["addPresetInfo"])) {
                    header('Location: index.php');
                    die();
                } else {
                    echo $result["addPresetInfo"];
                }
            }
        }
    }

    public function editPreset(): void
    {
        if (isset($_GET["preset"])) {
            if (!empty($this->presetList["preset"])) {
                echo "<form id='editPreset' method='post'>";
                echo "<button type='submit' id='editButton' name='editPreset' value='" . $_GET["preset"] . "'> Edit: " . $this->presetList["preset"] . "</button><br/>";
                echo "<form>";
            }
        }
        if (isset($_POST["editPreset"])) {
            echo "<form id='editValues' method='post'>";
            echo "<label for=\"preEditName\">Preset name:</label><br>
                  <input type=\"text\" id=\"preEditName\" name=\"preEditName\"><br>
                  <select name=\"effect\" id=\"effect\">
                        <option value=\"none\">Choose Effect</option>
                        <option value=\"1\">Distortion</option>
                        <option value=\"2\">Reverb</option>
                        <option value=\"3\">Delay</option>
                  </select>";
            echo "<button type='submit' id='saveButton' name='saveEdit' value='" . $_GET["preset"] . "'>Save</button><br/>";
            echo "</form>";
        }
        if (isset($_POST["saveEdit"])) {
            $preEditName = htmlspecialchars($_POST["preEditName"], ENT_QUOTES, 'UTF-8');
            $this->IPresetMethods->editPresetInfo($_GET["preset"], $preEditName ?? null, $_POST["effect"]);
            header('Location: index.php?preset=' . $_GET["preset"] . '');
            die();
        }
    }

    public function deletePresetUI(): void
    {
        if (isset($_GET["preset"])) {
            if (!empty($this->presetList["preset"])) {
                echo "<form id='deletePreset' method='post'>";
                echo "<button type='submit' id='delButton' name='deletePreset' value='" . $_GET["preset"] . "'> Delete: " . $this->presetList["preset"] . "</button><br/>";
                echo "</form>";
            }
        }
        if (isset($_POST["deletePreset"])) {
            $this->IPresetMethods->deletePresetInfo($_POST['deletePreset']);
            header('Location: index.php');
            die();
        }
    }
}
