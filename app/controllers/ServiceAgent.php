<?php
class ServiceAgent extends Controller
{
    private $user = [
            'role' => ROLE_SERVICE_AGENT,
        ];

    public function profile(){
        $data = [
                'user' => $this->user,
            ];

        $this->view('pages/service_agent/profile', $data,'dashboard');
    }

}

?>