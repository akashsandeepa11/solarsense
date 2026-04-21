<div class="col-md-6 form-group">
                            <?php 
                            $systemtypeOptions = [
                                'hybrid' => 'hybrid',
                                'offgrid' => 'offgrid',
                                'on-grid' => 'on-grid',
                               
                            ];
                            $selectConfig = [
                                'id' => 'systemtype',
                                'name' => 'systemtype',
                                'label' => 'System Type',
                                'options' => $systemtypeOptions,
                                'value' => $data['systemtype'] ?? '',
                                'icon' => 'fas fa-solar-panel',
                                'required' => true,
                                'error' => $data['systemtype_err'] ?? '',
                                'placeholder' => 'Select system type'
                            ];
                            require APPROOT . '/views/inc/components/select_field.php';
                            ?>
                        </div>




                        $panelData = [
                'system_capacity' => $data['systemCapacity'],
                'panel_tilt' => $data['panelTilt'],
                'panel_azimuth' => $data['panelAzimuth'],
                'systemtype' => $data['systemtype'],
                'panel_brand' => $data['panelBrand'],
                'inverter_brand' => $data['inverterBrand'],
                'installation_date' => $data['installationDate'],
                'module_type' => $data['moduleType'],
                'array_type' => $data['arrayType'],
                'losses_pct' => $data['lossesPCT'],
                'dc_ac_ratio' => $data['dcAcRatio'],
                'inv_eff_pct' => $data['invEffPCT']
            ];
            // Call model to save data


                        $sqlHomeowner = 'INSERT INTO homeowner (user_id, company_id, address, contact, register_date, nic, district, ceb_account, systemtype) VALUES (:user_id, :company_id, :address, :contact, :register_date, :nic, :district, :ceb_account, :systemtype)';
            // Log the SQL and the values we'll bind to help diagnose missing field issues
            error_log('M_Fleet::add_customer homeowner SQL: ' . $sqlHomeowner);
            $binds = [
                ':user_id' => $userId,
                ':company_id' => $companyId,
                ':address' => $customerData['address'],
                ':contact' => $customerData['contact'],
                ':register_date' => date('Y-m-d'),
                ':nic' => $customerData['nic'],
                ':district' => $customerData['district'],
               
                ':ceb_account' => $customerData['ceb_account']

