<h1 class="text-center fw-bold my-4">Service Agent Report</h1>

<div style="max-width: 700px; margin: auto;" >
    
<form action="#" method="POST">
    <div>
      <?php
      // Grouped sections for report page
      $reportSections = [
          [
              'title' => 'Task Details',
              'fields' => [
                  ['id' => 'task-id', 'label' => 'Task ID', 'value' => 'T-2025-001', 'editable' => false],
                  ['id' => 'panel-id', 'label' => 'Panel ID', 'value' => 'P-1001', 'editable' => false],
                  ['id' => 'customer-name', 'label' => 'Customer Name', 'value' => 'John Doe', 'editable' => false],
                  ['id' => 'address', 'label' => 'Address', 'value' => '123 Main Street, Colombo 07', 'editable' => false],
                  ['id' => 'issue', 'label' => 'Issue Description', 'value' => 'Inverter Fault Detected', 'editable' => false],
                  ['id' => 'date', 'label' => 'Task Date', 'value' => date('Y-m-d'), 'editable' => false],
              ]
          ],
          [
              'title' => 'Service Details',
              'fields' => [
                  ['id' => 'actions-taken', 'label' => 'Actions Taken', 'value' => ' ', 'editable' => true,'required' => false,],
                  ['id' => 'replaced-parts', 'label' => 'Replaced Parts', 'value' => '', 'editable' => true,'required' => false,],
                  ['id' => 'time-spent', 'label' => 'Time Spent (hours)', 'value' => '', 'editable' => true,'required' => false,],
                  ['id' => 'technician-notes', 'label' => 'Technician Notes', 'value' => '', 'editable' => true,'required' => false,],
              ]
          ],
          [
              'title' => 'Verification',
              'fields' => [
                  ['id' => 'final-status', 'label' => 'Final Status', 'value' => '', 'editable' => true ,'required' => false],
                  ['id' => 'technician-name', 'label' => 'Technician Signature', 'value' => '', 'editable' => true,'required' => false],
              ]
          ]
      ];

      // Render all report sections
      foreach ($reportSections as $section):
      ?>
        <div class="card mb-4 shadow-sm border-0">
          <div class="card-header bg-white border-0">
            <h5 class="fw-semibold mb-0"><?php echo htmlspecialchars($section['title']); ?></h5>
          </div>
          <div class="card-body">
            <?php
            // Load each field using your reusable component
            foreach ($section['fields'] as $field) {
                require APPROOT . '/views/inc/components/profile_input_field.php';
            }
            ?>
          </div>
        </div>
      <?php endforeach; ?>

    </div>
  

  <!-- Buttons -->
   <div style="display: flex; justify-content: flex-end; align-items: center; gap: 15px; margin-top: 20px;">
    <button type="submit" class="btn btn-success px-5 py-2 me-2">Submit Report</button>
    <a href="<?php echo URLROOT; ?>/serviceagent/tasks" class="btn btn-secondary px-5 py-2">Cancel</a>
  </div>

</form>

</div>

