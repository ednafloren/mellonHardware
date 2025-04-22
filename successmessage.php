  <!-- Success message -->
  <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message" id="successMessage">
                <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>
