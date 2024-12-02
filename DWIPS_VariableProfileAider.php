<?php /** @noinspection PhpExpressionResultUnusedInspection */

/**
 * @package       DWIPS.Aider
 * @file          DWIPS_VariableProfileAider.php
 * @author        Daniel Weber <it@dan-web.de>
 * @copyright     2024 Daniel Weber
 * @license       https://creativecommons.org/licenses/by-nc-sa/4.0/ CC BY-NC-SA 4.0
 * @version       1.0
 */

namespace DWIPS\Aider {
    /**
     * Aider functions for the work with Variable Profiles.
     */
    trait DWIPS_VariableProfileAider
    {
        /**
         * Deletes a VariableProfile, if not used by other instances.
         *
         * @param string $Name Name of the VariableProfile to delete.
         */
        protected function DeleteVariableProfile(string $Name)
        {
            if (!IPS_VariableProfileExists($Name)) {
                goto end;
            }
            foreach (IPS_GetVariableList() as $VarID) {
                if (IPS_GetParent($VarID) == $this->InstanceID) {
                    continue;
                }
                if (IPS_GetVariable($VarID)['VariableProfile'] == $Name) {
                    goto end;
                }
                if (IPS_GetVariable($VarID)['VariableCustomProfile'] == $Name) {
                    goto end;
                }
            }
            IPS_DeleteVariableProfile($Name);
            end:
        }

        /**
         * Creates or updates a VariableProfile with associations and configures it.
         *
         * @param int $VarType Type of the VariableProfile.
         * @param string $Name Name of the profile.
         * @param string $Icon Name of the profile.
         * @param string $Prefix Prefix of the presentation.
         * @param string $Suffix Suffix of the presentation.
         * @param array $Associations Associations to be added to the profile.
         * @param int $MinValue Minimal value.
         * @param int $MaxValue Maximal value.
         * @param int $StepSize Step size between values.
         */
        protected function MaintainVariableProfileAssoc($VarType, $Name, $Icon, $Prefix, $Suffix, $Associations, $MinValue = null, $MaxValue = null, $StepSize = 0, $Digits = 0)
        {
            $this->MaintainVariableProfile($VarType, $Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits);
            $oldAssoc = IPS_GetVariableProfile($Name)['Associations'];
            $OldAssocValues = array_column($oldAssoc, 'Value');
            foreach ($Associations as $Association) {
                IPS_SetVariableProfileAssociation($Name, $Association[0], $this->Translate($Association[1]), $Association[2], $Association[3]);
                $OldAssocKey = array_search($Association[0], $OldAssocValues);
                if (!($OldAssocKey === false)) {
                    unset($OldAssocValues[$OldAssocKey]);
                }
            }
            foreach ($OldAssocValues as $OldAssocValue) {
                IPS_SetVariableProfileAssociation($Name, $OldAssocValue, '', '', -1);
            }
        }

        /**
         * Creates or updates a VariableProfile and configures it.
         * *
         * * @param int $VarType Type of the VariableProfile.
         * * @param string $Name Name of the profile.
         * * @param string $Icon Name of the profile.
         * * @param string $Prefix Prefix of the presentation.
         * * @param string $Suffix Suffix of the presentation.
         * * @param int $MinValue Minimal value.
         * * @param int $MaxValue Maximal value.
         * * @param int $StepSize Step size between values.
         *  * @param int $Digits Digits to be shown.
         */
        protected function MaintainVariableProfile($VarType, $Name, $Icon, $Prefix, $Suffix, $MinValue, $MaxValue, $StepSize, $Digits = 0)
        {
            if (IPS_VariableProfileExists($Name)) {
                $profile = IPS_GetVariableProfile($Name);
                if ($profile['ProfileType'] != $VarType) {
                    throw new Exception('Variable profile type does not match the existing profile ' . $Name, E_USER_WARNING);
                    }
            }else {
                IPS_CreateVariableProfile($Name, $VarType);
            }
            IPS_SetVariableProfileIcon($Name, $Icon);
            IPS_SetVariableProfileText($Name, $this->Translate($Prefix), $this->Translate($Suffix));
            if (($VarType != VARIABLETYPE_BOOLEAN) && ($VarType != VARIABLETYPE_STRING)) {
                IPS_SetVariableProfileValues($Name, $MinValue, $MaxValue, $StepSize);
            }
            if ($VarType == VARIABLETYPE_FLOAT) {
                IPS_SetVariableProfileDigits($Name, $Digits);
            }
        }

    }
}
?>
