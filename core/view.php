<?php
namespace Core
{
    class View
    {
        function render($viewName, $argumentArray = array())
        {
            if (is_string($viewName)) //render /view/$viewName.php file
            {
                foreach ($argumentArray as $key => $val)
                    $$key = $val;
                include("/view/headerView.php");
                include("/view/".$viewName."View.php");
                include("/view/footerView.php");
            } else { //print as a JSON
                header('Content-Type: application/json; charset=utf8');
                echo json_encode($viewName);
            }
        }
    }
}