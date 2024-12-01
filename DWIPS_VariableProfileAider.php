<?php /** @noinspection PhpExpressionResultUnusedInspection */

/**
 * @package       DWIPS.Aider
 * @file          DWIPS_VariableProfileAider.php
 * @author        Daniel Weber <it@dan-web.de>
 * @copyright     2024 Daniel Weber
 * @license       https://creativecommons.org/licenses/by-nc-sa/4.0/ CC BY-NC-SA 4.0
 * @version       1.0
 */

/**
 * Aider functions for the work with Variable Profiles.
 */
trait DWIPS_VariableProfileAider{

protected function d(IPSModule $Module, string $ProfileNamePrefix, string $ProfileName, array $Associations){
    $profilename = $ProfileNamePrefix . $this->Translate($ProfileName);
    if(IPS_VariableProfileExists($profilename)) {
        IPS_DeleteVariableProfile($profilename);
    }
    IPS_CreateVariableProfile($profilename, 1);
    IPS_SetVariableProfileValues($profilename, 0, 4, 1);
    foreach ($Associations as $Association) {
        IPS_SetVariableProfileAssociation($profilename, $Association['Value'], $Module->Translate($Association['Text']), $Association['Icon'], $Association['Color']);
    }
}
}
?>
