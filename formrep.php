
<?php $reportType = 'daily';
// Get the current date, month, and year
$currentDate = date('Y-m-d');
$currentMonth = date('Y-m');
$currentYear = date('Y');

$date = $currentDate;
$month = $currentMonth;
$year = $currentYear;?>
<script>
     function showInputFields() {
            var reportType = document.getElementById('report_type').value;
            document.getElementById('date-input').style.display = (reportType == 'date') ? 'block' : 'none';
            document.getElementById('month-input').style.display = (reportType == 'monthly') ? 'block' : 'none';
            document.getElementById('year-input').style.display = (reportType == 'annually') ? 'block' : 'none';
        }
</script>

   <!-- Form to select report type -->
   <form method="POST" action="" class="inputform"  >
            <label for="report_type">Report Type:</label>
            <select id="report_type" name="report_type" onchange="showInputFields();">
                <option value="daily" <?php if ($reportType == 'daily') echo 'selected'; ?>>Daily</option>
                <option value="date" <?php if ($reportType == 'date') echo 'selected'; ?>>Date</option>
                <option value="monthly" <?php if ($reportType == 'monthly') echo 'selected'; ?>>Monthly</option>
                <option value="annually" <?php if ($reportType == 'annually') echo 'selected'; ?>>Annually</option>
            </select>
            <div id="date-input" style="display: <?php echo ($reportType == 'date') ? 'block' : 'none'; ?>">
                <label for="date">Select Date:</label>
                <input type="date" name="date" value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : $currentDate; ?>">
            </div>
            <div id="month-input" style="display: <?php echo ($reportType == 'monthly') ? 'block' : 'none'; ?>">
                <label for="month">Select Month:</label>
                <input type="month" name="month" value="<?php echo isset($_POST['month']) ? htmlspecialchars($_POST['month']) : $currentMonth; ?>">
            </div>
            <div id="year-input" style="display: <?php echo ($reportType == 'annually') ? 'block' : 'none'; ?>">
                <label for="year">Select Year:</label>
                <input type="number" name="year" min="2000" max="2100" value="<?php echo isset($_POST['year']) ? htmlspecialchars($_POST['year']) : $currentYear; ?>">
            </div>
            <button type="submit" class="generate">Generate</button>
        </form>