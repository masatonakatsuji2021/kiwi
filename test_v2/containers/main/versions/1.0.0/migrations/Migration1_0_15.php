<?php

namespace kiwi\main\migrations;

use kiwi\core\Migration;

class Migration1_0_15 extends Migration {

    public function upgrade() : void {
        echo "<div>migration upgrade 1.0.15....</div>";        
    }

    public function downgrade() : void{
        echo "<div>migration downgrade 1.0.15....</div>";
    }
}