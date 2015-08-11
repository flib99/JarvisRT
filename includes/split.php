<?php

$message = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc pulvinar, enim at malesuada eleifend, elit neque scelerisque risus, non gravida nulla nisi dapibus nisi. In scelerisque, risus sit amet semper varius, leo justo gravida erat, sit amet malesuada ex est nec odio. Vivamus sed vestibulum metus. Integer quis mattis mauris. Suspendisse consectetur est sit amet augue suscipit, ut egestas lectus posuere. Donec placerat urna at erat aliquam sodales. Sed interdum, mi eu commodo consequat, justo sapien lobortis mi, non tristique velit neque at enim. Curabitur vitae orci aliquet, volutpat nulla ut, imperdiet magna. Cras congue placerat nulla eu suscipit. Nam varius nisl est, ac rutrum nulla consectetur posuere. Suspendisse eu sagittis augue. Morbi ultricies eleifend lacus, sed semper erat lobortis.";

$chunk = [];

if (str_word_count($message) > 100)
{
    $lineSplit = preg_replace( '~((?:\S*?\s){100})~', "$1\n", $message );
    
    $chunk = explode("\n", $lineSplit);
}

var_dump($chunk);
