<script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>
    
<div class="jumbotron">
  <h1>Financial History</h1>
  <div id="container" style="min-width: 300px; height: 200px; margin: 0 auto"></div>
</div>

<h1>Transactions</h1>
<div class="fluid-row">
  <?php 
    $transactions = GetTransactionsByTeamId($_SESSION['tid']);
    if($transactions != NULL){
      // Last column example <td style="text-align: right;"><span class="label label-important">10 Players</span></td>
  ?>
    <table class="table table-striped">
      <tr>
        <th width="50%">Transaction</th>
        <th width="10%">Amount</th>
        <th width="20%">Date</th>
        <th width="20%"></th>
      </tr>
      <?php foreach ($transactions as $t) {
          $class = ($t->amount > 0 ? "success" : "error");
      ?>
      <tr class="<?=$class?>">
        <td><?=$t->message?></td>
        <td><?=$t->amount?></td>
        <td><?=$t->when?></td>
        <td></td>
      </tr>
      <?php } ?>
    </table>
  <?php } else { ?>
    <h4>No finance history found.</h4>
  <?php } ?>
</div>