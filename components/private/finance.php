<script src="js/highcharts.js"></script>

  <?php 
    $transactions = GetTransactionsByTeamId($_SESSION['tid'], 10);
    $team = GetTeamById($_SESSION['tid']);

    if($transactions != NULL){
      // Last column example <td style="text-align: right;"><span class="label label-important">10 Players</span></td>
      // plotting the transactions
      // First the current point
      $categories[0] = date("Y-j-n");
      $data[0] = $team->assets; 
      $counter = 1;
      //$revtrans = array_reverse($transactions);
      foreach ($transactions as $t) {
        $categories[$counter] = substr($t->when, 0, 10);
        $data[$counter] = $data[$counter-1] - $t->amount;
        $counter++;
      }
  ?>

<div class="jumbotron">
  <h1>Financial History</h1>
  <div id="container" style="min-width: 300px; height: 200px; margin: 0 auto"></div>
</div>

<script language="javascript">
  // Example Chart
  $(function () {
      var chart;
      $(document).ready(function() {
          chart = new Highcharts.Chart({
              chart: {
                  renderTo: 'container',
                  type: 'line',
                  marginRight: 130,
                  marginBottom: 25
              },
              title: {
                  text: ''
              },
              xAxis: {
                  categories: [
                  <?php 
                    $str = "";
                    $revcat = array_reverse($categories);
                    foreach ($revcat as $c) {
                      $str .= "'".$c."', ";
                    }
                    $str = substr($str, 0, strlen($str)-2);
                    echo $str;
                  ?>
                  ]
              },
              yAxis: {
                  title: {
                      text: 'Amount x 1000 ($)'
                  },
                  plotLines: [{
                      value: 0,
                      width: 1,
                      color: '#808080'
                  }]
              },
              tooltip: {
                  formatter: function() {
                          return this.y +'K $';
                  }
              },
              legend: {
                  layout: 'vertical',
                  align: 'right',
                  verticalAlign: 'top',
                  x: -10,
                  y: 100,
                  borderWidth: 0
              },
              series: [{
                  name: 'Total Assets',
                  data: [<?php                   
                    $str = "";
                    $revdata = array_reverse($data);
                    foreach ($revdata as $d) {
                      $str .= $d.", ";
                    }
                    $str = substr($str, 0, strlen($str)-2);
                    echo $str;              
                  ?>]
              }]
          });
      });
      
  });
</script>

<h1>Transactions</h1>
<div class="fluid-row">
    <table class="table table-striped">
      <tr>
        <th width="50%">Transaction</th>
        <th width="10%">Amount</th>
        <th width="20%">Date</th>
        <th width="20%"></th>
      </tr>
      <?php 

      foreach ($transactions as $t) {
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
</div>
  <?php } else { ?>
    <h4>No financial history found.</h4>
  <?php } ?>