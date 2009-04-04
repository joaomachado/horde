<?php
/**
 * Horde_Form for deleting ledgers.
 *
 * $Horde: fima/lib/Forms/DeleteLedger.php,v 1.0 2008/06/19 18:15:08 trt Exp $
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/gpl.html.
 *
 * @package Fima
 */

/** Variables */
require_once 'Horde/Variables.php';

/** Horde_Form */
require_once 'Horde/Form.php';

/** Horde_Form_Renderer */
require_once 'Horde/Form/Renderer.php';

/**
 * The Fima_DeleteLedgerForm class provides the form for
 * deleting a ledger.
 *
 * @author  Thomas Trethan <thomas@trethan.net>
 * @package Fima
 */
class Fima_DeleteLedgerForm extends Horde_Form {

    /**
     * Ledger being deleted
     */
    var $_ledger;

    function Fima_DeleteLedgerForm(&$vars, &$ledger)
    {
        $this->_ledger = &$ledger;
        parent::Horde_Form($vars, sprintf(_("Delete %s"), $ledger->get('name')));

        $this->addHidden('', 'l', 'text', true);
        $this->addVariable(sprintf(_("Really delete the ledger \"%s\"? This cannot be undone and all data on this ledger will be permanently removed."), $this->_ledger->get('name')), 'desc', 'description', false);

        $this->setButtons(array(_("Delete"), _("Cancel")));
    }

    function execute()
    {
        // If cancel was clicked, return false.
        if ($this->_vars->get('submitbutton') == _("Cancel")) {
            return false;
        }

        if ($this->_ledger->get('owner') != Auth::getAuth()) {
            return PEAR::raiseError(_("Permission denied"));
        }

        // Delete the ledger.
        $storage = &Fima_Driver::singleton($this->_ledger->getName());
        $result = $storage->deleteAll();
        if (is_a($result, 'PEAR_Error')) {
            return PEAR::raiseError(sprintf(_("Unable to delete \"%s\": %s"), $this->_ledger->get('name'), $result->getMessage()));
        } else {
            // Remove share and all groups/permissions.
            $result = $GLOBALS['fima_shares']->removeShare($this->_ledger);
            if (is_a($result, 'PEAR_Error')) {
                return $result;
            }
        }

        // Make sure we still own at least one ledger.
        if (count(Fima::listLedgers(true)) == 0) {
            // If the default share doesn't exist then create it.
            if (!$GLOBALS['fima_shares']->exists(Auth::getAuth())) {
                require_once 'Horde/Identity.php';
                $identity = &Identity::singleton();
                $name = $identity->getValue('fullname');
                if (trim($name) == '') {
                    $name = Auth::removeHook(Auth::getAuth());
                }
                $ledger = &$GLOBALS['fima_shares']->newShare(Auth::getAuth());
                if (is_a($ledger, 'PEAR_Error')) {
                    return;
                }
                $ledger->set('name', sprintf(_("%s's Ledger"), $name));
                $GLOBALS['fima_shares']->addShare($ledger);
            }
        }

        return true;
    }

}
