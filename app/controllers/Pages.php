<?php
    class Pages extends Controller {

        private $quotationModel;

        public function __construct() {
            $this->quotationModel = $this->model('M_Quotation');
        }

        public function index() {
            $this->landing();
        }

        public function about() {
            $this->view('pages/about');
        }

        public function landing() {
            $stats      = $this->quotationModel->get_landing_stats();
            $installers = $this->quotationModel->get_verified_installers();

            $data = [
                'homeowner_count' => $stats['homeowner_count'],
                'installer_count' => $stats['installer_count'],
                'installers'      => $installers,
            ];

            $this->view('pages/landing', $data);
        }

        /**
         * POST /pages/submit_quotation
         * Saves a quotation request from the landing page.
         * Returns JSON.
         */
        public function submit_quotation() {
            header('Content-Type: application/json');

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
                exit;
            }

            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input) {
                // Fall back to form-encoded body
                $input = $_POST;
            }

            // Basic validation
            $required = ['company_id', 'customer_name', 'customer_phone', 'customer_email'];
            foreach ($required as $field) {
                if (empty($input[$field])) {
                    echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
                    exit;
                }
            }

            $data = [
                'company_id'      => (int) $input['company_id'],
                'customer_name'   => trim($input['customer_name']),
                'customer_phone'  => trim($input['customer_phone']),
                'customer_email'  => trim($input['customer_email']),
                'customer_address'=> trim($input['customer_address'] ?? ''),
                'bill_amount'     => trim($input['bill_range']  ?? ''),
                'roof_type'       => trim($input['roof_type']   ?? ''),
                'property_type'   => trim($input['property_type'] ?? ''),
                'existing_system' => trim($input['existing_system'] ?? ''),
            ];

            $result = $this->quotationModel->save_quotation($data);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Quotation submitted successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to save quotation. Please try again.']);
            }
            exit;
        }

        // Render 404 page
        public function error404() {
            $this->view('pages/404');
        }

        // Render 403 Forbidden page
        public function error403() {
            $this->view('pages/403');
        }

        public function email_verification(){
            $this->view('pages/auth/email_verification');
        }
        public function reset(){
            $this->view('pages/auth/reset_password_email');
        }
    }
?>