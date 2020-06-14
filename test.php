<?php
function convertDateFormate($date){
    return date('Y-m-d', strtotime(str_replace('/','-',$date)));
}

function calculateDiffBetweenDate($date1, $date2){
    $time1 = strtotime($date1);
    $time2 = strtotime($date2);
    return abs($time2 - $time1)/(60*60);
}

$arrayDate = array(
    array('date'=>'10/2/2019','time'=>'11:00','action'=>'start'),
    array('date'=>'11/2/2019','time'=>'12:00','action'=>'end'),
    array('date'=>'12/2/2019','time'=>'23:00','action'=>'start'),
    array('date'=>'13/2/2019','time'=>'04:00','action'=>'end'),
    array('date'=>'13/2/2019','time'=>'10:00','action'=>'start'),
    array('date'=>'13/2/2019','time'=>'16:00','action'=>'end'),
    array('date'=>'17/2/2019','time'=>'17:00','action'=>'start'),
    array('date'=>'17/2/2019','time'=>'23:00','action'=>'end')
);

$timesheet=array();
$dailyhours=array();
$record_count=0;
foreach($arrayDate as $record){
    if($record['action']=='start'){
        $timesheet[$record_count]['start_date']=$record['date'];
        $timesheet[$record_count]['shift_start']=convertDateFormate($record['date']).' '.$record['time'];
        
    }else if($record['action']=='end'){
        $timesheet[$record_count]['end_date']=$record['date'];
        $timesheet[$record_count]['shift_end']=convertDateFormate($record['date']).' '.$record['time'];

        $diff=calculateDiffBetweenDate($timesheet[$record_count]['shift_start'],$timesheet[$record_count]['shift_end']);

        if($timesheet[$record_count]['start_date']===$timesheet[$record_count]['end_date']){
            $timesheet[$record_count]['diff']=$diff;

            $dailyhours[$record['date']]=(isset($dailyhours[$record['date']]))?($dailyhours[$record['date']] + $diff): $diff;
        }else{
            $diff1=calculateDiffBetweenDate($timesheet[$record_count]['shift_start'],convertDateFormate($timesheet[$record_count]['start_date']).' 24:00:00');

            $diff2=calculateDiffBetweenDate(convertDateFormate($timesheet[$record_count]['end_date']).' 00:00:00', $timesheet[$record_count]['shift_end']);

            $dailyhours[$timesheet[$record_count]['start_date']]=(isset($dailyhours[$timesheet[$record_count]['start_date']]))?($dailyhours[$timesheet[$record_count]['start_date']] + $diff1): $diff1;

            $dailyhours[$timesheet[$record_count]['end_date']]=(isset($dailyhours[$timesheet[$record_count]['end_date']]))?($dailyhours[$timesheet[$record_count]['end_date']] + $diff2): $diff2;

        }
        
        $record_count++;
    }
}?>

<table width="100%" border="1">
    <thead>
        <tr>
            <th>Date</th>
            <th>Hours worked</th>
        </tr>
    </thead>
  <tbody>
    <?php if(!empty($dailyhours) && count($dailyhours) > 0){
        foreach($dailyhours as $date=>$hr){ ?>
            <tr>
                <td><?php echo $date;?></td>
                <td><?php echo $hr;?></td>
            </tr>
        <?php }
    }?>
  </tbody>
</table>