<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title><?php printf("Shipping Labels - %s",implode(',',$orders)) ?></title>
	<style type="text/css">
        body{margin:15px;position:relative}
        td.packing-slip-wrapper{width:50%;vertical-align:top}
        .packing-slip-content{
            border:2px dashed #000;
            padding:1% 2%;
            margin:0 1% 1%;
        }
    </style>
</head>
<body class="packing-slips">
    <table width="100%">
    <?php
    // loop orders
    foreach($orders as $i=>$order_id):
        // html content for each label
        $content = apply_filters($this->plugin_name.'-label_content_html',$order_id);
        if(!$content) continue;
    ?>
        
        <?php if($i%2==0): ?>
        <tr>
        <?php endif ?>
            
        <td class="packing-slip-wrapper">
        <?php echo $content ?>
        </td>

        <?php if($i%2!=0): ?>
        </tr>
        <?php endif ?>
    
    <?php endforeach; ?>

    </table>
</body>
</html>