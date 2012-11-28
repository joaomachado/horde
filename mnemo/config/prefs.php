<?php
/**
 * See horde/config/prefs.php for documentation on the structure of this file.
 *
 * IMPORTANT: DO NOT EDIT THIS FILE!
 * Local overrides MUST be placed in prefs.local.php or prefs.d/.
 * If the 'vhosts' setting has been enabled in Horde's configuration, you can
 * use prefs-servername.php.
 */

$prefGroups['display'] = array(
    'column' => _("General Preferences"),
    'label' => _("Display Preferences"),
    'desc' => _("Change your note sorting and display preferences."),
    'members' => array('show_notepad', 'sortby', 'sortdir')
);

$prefGroups['share'] = array(
    'column' => _("General Preferences"),
    'label' => _("Default Notepad"),
    'desc' => _("Choose your default Notepad."),
    'members' => array('default_notepad')
);

$prefGroups['deletion'] = array(
    'column' => _("General Preferences"),
    'label' => _("Delete Confirmation"),
    'desc' => _("Delete button behaviour"),
    'members' => array('delete_opt')
);


// show a notepad column in the list view?
$_prefs['show_notepad'] = array(
    'value' => 0,
    'type' => 'checkbox',
    'desc' => _("Should the Notepad be shown in its own column in the List view?")
);

// user preferred sorting column
$_prefs['sortby'] = array(
    'value' => Mnemo::SORT_DESC,
    'type' => 'enum',
    'enum' => array(
        Mnemo::SORT_DESC => _("Note Text"),
        Mnemo::SORT_CATEGORY => _("Note Category"),
        Mnemo::SORT_NOTEPAD => _("Notepad"),
        Mnemo::SORT_MOD_DATE => _("Modification Date"),
    ),
    'desc' => _("Default sorting criteria:")
);

// user preferred sorting direction
$_prefs['sortdir'] = array(
    'value' => 0,
    'type' => 'enum',
    'enum' => array(
        Mnemo::SORT_ASCEND => _("Ascending"),
        Mnemo::SORT_DESCEND => _("Descending")
    ),
    'desc' => _("Default sorting direction:")
);

// user note categories
$_prefs['memo_categories'] = array(
    'value' => ''
);

// category highlight colors
$_prefs['memo_colors'] = array(
    'value' => ''
);

// default notepad
// Set locked to true if you don't want users to have multiple notepads.
$_prefs['default_notepad'] = array(
    'value' => ''
    'type' => 'enum',
    'enum' => array(),
    'desc' => _("Your default notepad:"),
    'on_init' => function($ui) {
        $enum = array();
        foreach (Mnemo::listNotepads(false, Horde_Perms::EDIT) as $key => $val) {
            $enum[$key] = Mnemo::getLabel($val);
        }
        $ui->prefs['default_notepad']['enum'] = $enum;
    },
);

// store the notepads to diplay
$_prefs['display_notepads'] = array(
    'value' => 'a:0:{}'
);

// preference for delete confirmation dialog.
$_prefs['delete_opt'] = array(
    'value' => 1,
    'type' => 'checkbox',
    'desc' => _("Do you want to confirm deleting entries?")
);
