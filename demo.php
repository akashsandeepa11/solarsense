            if (empty($data['contactNumber']) || !preg_match('/^[0-9\-\+\s\(\)]+$/', $data['contactNumber'])) {
                $data['contactNumber_err'] = "Please enter a valid contact number";
            }

            if ((substr($data['contactNumber'], 0, 2) != 07)) {
                $data['contactNumber_err'] = "Not contain 07";
            }
            
            if (strlen((string)$data['contactNumber'] != 10)){
                $data['contactNumber_err'] = "Not contain 10 digit";
            }

            if (strlen((string)$data['nic']) != 12) {
                $data['nic_err'] = "Please enter valid NIC/ID number";
            }

            //old and new
            $nic = strtoupper(trim((string)($data['nic'] ?? '')));

            $isOld = (strlen($nic) == 10) && (preg_match('/^[0-9]{9}[VX]$/', $nic));
            $isNew = (strlen($nic) == 12) && (ctype_digit($nic));

            if (!($isOld || $isNew)) {
                $data['nic_err'] = "Please enter valid NIC";
            }


            //days_since
            ['key' => 'days_since', 'label' => 'Days Since Install']