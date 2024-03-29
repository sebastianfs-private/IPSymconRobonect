<?

require_once '/var/lib/symcon/modules/IPSymconRobonect/libs/common.php';  // globale Funktionen
require_once '/var/lib/symcon/modules/IPSymconRobonect/libs/library.php';  // modul-bezogene Funktionen

class RobonectConfig extends IPSModule
{
    public function Create()
    {
		parent::Create();
		
		$this->RegisterPropertyString('ip', '');
        $this->RegisterPropertyString('user', '');
        $this->RegisterPropertyString('password', '');
    }
	
	public function ApplyChanges()
    {
        parent::ApplyChanges();
		
		$ip = $this->ReadPropertyString('ip');
        $user = $this->ReadPropertyString('user');
        $password = $this->ReadPropertyString('password');

        $ok = true;
        if ($ip == '' || $user == '' || $password == '') {
            $ok = false;
        }
        $this->SetStatus($ok ? IS_ACTIVE : IS_UNAUTHORIZED);
    }
	
    public function GetConfigurationForm()
    {
        $formElements = [];
		$formElements[] = [
							'type' => 'ValidationTextBox',
							'name' => 'ip',
							'caption' => 'IP-Address'
						];
        $formElements[] = [
							'type' => 'ValidationTextBox',
							'name' => 'user',
							'caption' => 'User'
						];
        $formElements[] = [
							'type' => 'PasswordTextBox',
							'name' => 'password',
							'caption' => 'Password'
						];

        $options = [];
		$options[] = ['label' => 'Test', 'value' => 'Test'];
		
		$ip = $this->ReadPropertyString('ip');
        $user = $this->ReadPropertyString('user');
        $password = $this->ReadPropertyString('password');

        if ($ip != '' ||  $user != '' || $password != '') {
            // $status = $this->GetStatus();
            // if ($status != '') {
				// $name = $status['name'];
                // $options[] = ['label' => $name, 'value' => $name];
            // }
			
			$debug = True;

			$getDataUrl = array(
				"status"  => "/json?cmd=status",
				"version" => "/json?cmd=version",
				"error"   => "/json?cmd=error"
			);
				
			// $content = $this->url_get_contents($getDataUrl['status'], $debug);

			// $status = json_decode($content, true);

			// if($status['successful'] == true){
			// 	$name = $status['name'];
			// 	$options[] = ['label' => $name, 'value' => $name];
			// }
        }

        $formActions = [];
        $formActions[] = [
							'type' => 'Select',
							'name' => 'mower_name',
							'caption' => 'Mower-Name',
							'options' => $options
						];
        $formActions[] = [
                            'type'    => 'Button',
                            'caption' => 'Import of mower',
                            'confirm' => 'Triggering the function creates the instances for the selected Robonect-device. Are you sure?',
                            'onClick' => 'RobonectConfig_Doit($id, $mower_name);'
                        ];
        // $formActions[] = [
							// 'type' => 'Label',
							// 'label' => '____________________________________________________________________________________________________'
						// ];
        // $formActions[] = [
                            // 'type'    => 'Button',
                            // 'caption' => 'Module description',
                            // 'onClick' => 'echo "https://github.com/demel42/IPSymconAutomowerConnect/blob/master/README.md";'
                        // ];
		
        $formStatus = [];
        $formStatus[] = ['code' => IS_CREATING, 'icon' => 'inactive', 'caption' => 'Instance getting created'];
        $formStatus[] = ['code' => IS_ACTIVE, 'icon' => 'active', 'caption' => 'Instance is active'];
        $formStatus[] = ['code' => IS_DELETING, 'icon' => 'inactive', 'caption' => 'Instance is deleted'];
        $formStatus[] = ['code' => IS_INACTIVE, 'icon' => 'inactive', 'caption' => 'Instance is inactive'];
        $formStatus[] = ['code' => IS_NOTCREATED, 'icon' => 'inactive', 'caption' => 'Instance is not created'];

        $formStatus[] = ['code' => IS_UNAUTHORIZED, 'icon' => 'error', 'caption' => 'Instance is inactive (unauthorized)'];
        $formStatus[] = ['code' => IS_SERVERERROR, 'icon' => 'error', 'caption' => 'Instance is inactive (server error)'];
        $formStatus[] = ['code' => IS_HTTPERROR, 'icon' => 'error', 'caption' => 'Instance is inactive (http error)'];
        $formStatus[] = ['code' => IS_INVALIDDATA, 'icon' => 'error', 'caption' => 'Instance is inactive (invalid data)'];
        $formStatus[] = ['code' => IS_DEVICE_MISSING, 'icon' => 'error', 'caption' => 'Instance is inactive (device missing)'];

        return json_encode(['elements' => $formElements, 'actions' => $formActions, 'status' => $formStatus]);
    }
}