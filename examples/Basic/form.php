<?php if(!defined('INCLUDE')) { die(); } else { global $request; } ?>

<form name="form1" method="post" action=" <?php $request->translate('/login') ?> ">
<table>
    <tr>
        <td> Text: </td>
        <td> <input name="text" type="textbox"/> </td>
    </tr>
    <tr>    
        <td> <input type="submit" value="Send"> </td>
    </tr>
</table>
</form>
