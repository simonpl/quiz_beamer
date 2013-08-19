<?php
function calculate_points($id)
{
    if($id <= 4)
    {
        return 20;
    }
    else if($id<= 8)
    {
        return 40;
    }
    else if($id <= 12)
    {
        return 60;
    }
    else if($id <= 16)
    {
        return 80;
    }
    else
    {
        return 100;
    }
}
?>
