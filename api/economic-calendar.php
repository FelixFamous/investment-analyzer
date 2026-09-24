<?php
/**
 * GET /api/economic-calendar.php?days=30
 */
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$days = max(7, min(90, (int)($_GET['days'] ?? 30)));

$start = new DateTimeImmutable('today', new DateTimeZone('UTC'));
$end   = $start->modify("+{$days} days");

$events = [];

$FIXED = [
    ['2026-01-27', 'FOMC Meeting Begins', 'high',    'Fed',     'Two-day policy meeting; rate decision tomorrow'],
    ['2026-01-28', 'FOMC Rate Decision',  'high',    'Fed',     'Federal funds rate announced at 2:00 PM ET'],
    ['2026-03-17', 'FOMC Meeting Begins', 'high',    'Fed',     'Two-day policy meeting'],
    ['2026-03-18', 'FOMC Rate Decision',  'high',    'Fed',     'Fed rate decision + press conference'],
    ['2026-05-05', 'FOMC Meeting Begins', 'high',    'Fed',     'Two-day policy meeting'],
    ['2026-05-06', 'FOMC Rate Decision',  'high',    'Fed',     'Fed rate decision'],
    ['2026-06-16', 'FOMC Meeting Begins', 'high',    'Fed',     'Two-day policy meeting'],
    ['2026-06-17', 'FOMC Rate Decision',  'high',    'Fed',     'Fed rate decision + dot plot'],
    ['2026-07-28', 'FOMC Meeting Begins', 'high',    'Fed',     'Two-day policy meeting'],
    ['2026-07-29', 'FOMC Rate Decision',  'high',    'Fed',     'Fed rate decision'],
    ['2026-09-15', 'FOMC Meeting Begins', 'high',    'Fed',     'Two-day policy meeting'],
    ['2026-09-16', 'FOMC Rate Decision',  'high',    'Fed',     'Fed rate decision'],
    ['2026-11-03', 'FOMC Meeting Begins', 'high',    'Fed',     'Two-day policy meeting'],
    ['2026-11-04', 'FOMC Rate Decision',  'high',    'Fed',     'Fed rate decision'],
    ['2026-12-15', 'FOMC Meeting Begins', 'high',    'Fed',     'Two-day policy meeting'],
    ['2026-12-16', 'FOMC Rate Decision',  'high',    'Fed',     'Fed rate decision'],
];

foreach ($FIXED as $f) {
    $d = new DateTimeImmutable($f[0], new DateTimeZone('UTC'));
    if ($d >= $start && $d <= $end) {
        $events[] = ['date'=>$f[0],'time'=>'14:00','title'=>$f[1],'impact'=>$f[2],'category'=>$f[3],'note'=>$f[4]];
    }
}

$cursor = $start;
$doneMonths = [];
while ($cursor <= $end) {
    $year  = (int)$cursor->format('Y');
    $month = (int)$cursor->format('n');
    $key = "$year-$month";

    if (!isset($doneMonths[$key])) {
        $doneMonths[$key] = true;

        $firstDay = new DateTimeImmutable("$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-01", new DateTimeZone('UTC'));
        $dow = (int)$firstDay->format('N');
        $daysToFri = (5 - $dow + 7) % 7;
        $nfpDate = $firstDay->modify("+{$daysToFri} days");
        if ($nfpDate >= $start && $nfpDate <= $end) {
            $events[] = ['date'=>$nfpDate->format('Y-m-d'),'time'=>'13:30','title'=>'US Non-Farm Payrolls (NFP)','impact'=>'high','category'=>'Employment','note'=>'Monthly jobs report — moves USD and equities'];
        }

        $cpiDate = new DateTimeImmutable("$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-12", new DateTimeZone('UTC'));
        if ($cpiDate >= $start && $cpiDate <= $end) {
            $events[] = ['date'=>$cpiDate->format('Y-m-d'),'time'=>'13:30','title'=>'US CPI (Inflation)','impact'=>'high','category'=>'Inflation','note'=>'Consumer Price Index — key Fed input'];
        }

        $ppiDate = new DateTimeImmutable("$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-14", new DateTimeZone('UTC'));
        if ($ppiDate >= $start && $ppiDate <= $end) {
            $events[] = ['date'=>$ppiDate->format('Y-m-d'),'time'=>'13:30','title'=>'US PPI (Producer Prices)','impact'=>'medium','category'=>'Inflation','note'=>'Wholesale inflation gauge'];
        }

        $retail = new DateTimeImmutable("$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . "-15", new DateTimeZone('UTC'));
        if ($retail >= $start && $retail <= $end) {
            $events[] = ['date'=>$retail->format('Y-m-d'),'time'=>'13:30','title'=>'US Retail Sales','impact'=>'medium','category'=>'Consumer','note'=>'Consumer spending indicator'];
        }
    }

    $cursor = $cursor->modify('+1 day');
}

$EARNINGS = [
    'AAPL'  => ['2026-01-30', '2026-05-01', '2026-07-31', '2026-10-30'],
    'MSFT'  => ['2026-01-23', '2026-04-24', '2026-07-24', '2026-10-23'],
    'NVDA'  => ['2026-02-20', '2026-05-22', '2026-08-21', '2026-11-20'],
    'TSLA'  => ['2026-01-24', '2026-04-23', '2026-07-23', '2026-10-22'],
    'AMD'   => ['2026-01-28', '2026-04-29', '2026-07-29', '2026-10-28'],
    'META'  => ['2026-01-29', '2026-04-30', '2026-07-30', '2026-10-29'],
    'GOOGL' => ['2026-01-28', '2026-04-29', '2026-07-29', '2026-10-28'],
    'AMZN'  => ['2026-02-04', '2026-05-06', '2026-08-05', '2026-11-04'],
    'NFLX'  => ['2026-01-21', '2026-04-22', '2026-07-22', '2026-10-21'],
];

foreach ($EARNINGS as $sym => $dates) {
    foreach ($dates as $d) {
        $dd = new DateTimeImmutable($d, new DateTimeZone('UTC'));
        if ($dd >= $start && $dd <= $end) {
            $events[] = ['date'=>$d,'time'=>'20:00','title'=>$sym.' Earnings','impact'=>'high','category'=>'Earnings','note'=>$sym.' quarterly results','symbol'=>$sym];
        }
    }
}

usort($events, function ($a, $b) {
    if ($a['date'] === $b['date']) return strcmp($a['time'], $b['time']);
    return strcmp($a['date'], $b['date']);
});

echo json_encode(['events' => $events, 'days' => $days]);