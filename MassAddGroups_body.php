<?php

class SpecialMassAddGroups extends SpecialPage {
	function __construct() {
		parent::__construct('MassUserRights' , 'userrights' );
	}

	function execute( $par ) {
		global $wgOut, $wgUser;

		$wgOut->setArticleRelated( false );

		wfLoadExtensionMessages( 'MassAddGroups' );

		if( !$wgUser->isAllowed( 'userrights' ) ) {
			$wgOut->permissionRequired( 'userrights' );
			return;
		}

		$wgOut->setPagetitle( wfMsg( 'massaddgroups' ) );
		if (IsSet($_FILES['users_file'])) {
			$wgOut->addHTML( $this->AnalizeUsers($_FILES['users_file']) );
		} else {
			$wgOut->addHTML( $this->MakeForm() );
		}
	}

	function MakeForm() {
		global $wgLang;

		$titleObj = SpecialPage::getTitleFor( 'MassUserRights' );
		$action = $titleObj->escapeLocalURL();
		$fileFormat = $wgLang->commaList( array(
			wfMsg( 'massaddgroups-name' ),
			wfMsg( 'massaddgroups-action' ),
			wfMsg( 'massaddgroups-groups' ) ) );
		$output ='<form enctype="multipart/form-data" method="post"  action="'.$action.'">';
		$output.='<dl><dt>'. wfMsg( 'massaddgroups-form-file' ) . '</dt><dd>' . $fileFormat . '.</dd></dl>';
		$output.='<fieldset><legend>' . wfMsg('massaddgroups-uploadfile') . '</legend>';
		$output.='<table border=0 a-valign=center width=100%>';
		$output.='<tr><td align=right width=160>'.wfMsg( 'massaddgroups-form-caption' ).': </td><td><input name="users_file" type="file" size=40 /></td></tr>';
		$output.='<tr><td align=right></td><td><input type="submit" value="'.wfMsg( 'massaddgroups-form-button' ).'" /></td></tr>';
		$output.='</table>';
		$output.='</fieldset>';
		$output.='</form>';
		return $output;
	}

	function AnalizeUsers($fileinfo) {
		global $IP, $wgOut;
		$dbw = wfGetDB( DB_MASTER );
		require_once "$IP/includes/User.php";

		$summary=array('all'=>0,'updated'=>0);
		$filedata=explode("\n",rtrim(file_get_contents($fileinfo['tmp_name'])));
		$output='<h2>'.wfMsg( 'massaddgroups-log' ).'</h2>';

		foreach ($filedata as $line=>$newuserstr) {
			$newuserarray=explode(',', trim( $newuserstr ) );
			if (count($newuserarray)<3) {
				$output.= wfMsg( 'massaddgroups-user-invalid-format', $line+1 ) . '<br />';
				continue;
			}
			$NextUser=User::newFromName( $newuserarray[0] );
			$uid=$NextUser->idForName();
			if ($uid===0) {
				$output.= wfMsg( 'massaddgroups-user-skipped', $newuserarray[0] ) . '<br />';
			} else {
				if (in_array($newuserarray[2], $NextUser->getGroups()) && $newuserarray[1] == "add") {
					$output.= wfMsg( 'massaddgroups-user-skipped-has', $newuserarray[0] ) . '<br />';
				} else {
					if (!in_array($newuserarray[2], $NextUser->getGroups()) && $newuserarray[1] == "remove") {
						$output.= wfMsg( 'massaddgroups-user-skipped-has', $newuserarray[0] ) . '<br />';
					} else {
						if ($newuserarray[1] == "add") {
							$dbw->insert('user_groups', array('ug_user'=>$uid, 'ug_group'=>$newuserarray[2]));
						} else {
							$dbw->delete('user_groups', array('ug_user'=>$uid, 'ug_group'=>$newuserarray[2]));
						}
						$output.= wfMsg( 'massaddgroups-user-updated', $newuserarray[0] ).'<br />';
						$summary['updated']++;
					}
				}
			}
			$summary['all']++;
		}

		$output.='<b>'.wfMsg( 'massaddgroups-log-summary' ).'</b><br />';
		$output.=wfMsg( 'massaddgroups-log-summary-all' ).' : '.$summary['all'].'<br />';
		$output.=wfMsg( 'massaddgroups-log-summary-updated' ).' : '.$summary['updated'];

		return $output;
	}
}
