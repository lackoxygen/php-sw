<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2020/9/21
 * Time: 14:49
 */

/**
 * @param string $content
 */
function println(string $content){
    fwrite(STDOUT, $content . "\n");
}
