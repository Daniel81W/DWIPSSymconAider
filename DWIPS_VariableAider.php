<?php /** @noinspection PhpExpressionResultUnusedInspection */

/**
 * @package       DWIPS.Aider
 * @file          DWIPS_VariableAider.php
 * @author        Daniel Weber <it@dan-web.de>
 * @copyright     2024 Daniel Weber
 * @license       https://creativecommons.org/licenses/by-nc-sa/4.0/ CC BY-NC-SA 4.0
 * @version       1.0
 */

namespace DWIPS\Aider {
    /**
     * Aider functions for the work with Variables.
     */
    trait DWIPS_VariableAider
    {

        static $count = 1;
        /**
         * Deletes a Variable.
         *
         * @param string $Ident Ident of the Variable to delete.
         */
        protected function DeleteVariable(string $Ident)
        {
            $this->MaintainVariable($Ident, "", "", "", 0, false);
        }


        /**
         * Creates or updates a Variable and configures it.
         * *
         * * @param int $Ident Ident for the Variable.
         * * @param string $Name Name of the variable.
         * * @param string $VarType Taype of the variable.
         * * @param string $Profile Profile of the variable.
         */
        protected function CreateOrUpdateVariable($Ident, $Name, $VarType, $Profile, $ActionEnabled = false){
            $this->MaintainVariable($Ident, $Name, $VarType, $Profile, DWIPS_VariableAider::$count, true);
            if($ActionEnabled){
                $this->EnableAction($Ident);
            }else{
                $this->DisableAction($Ident);
            }
            IPS_SetPosition(IPS_GetObjectIDByIdent ($Ident,  $this->InstanceID) , DWIPS_VariableAider::$count);
            DWIPS_VariableAider::$count++;
        }

    }
}
?>
