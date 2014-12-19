<?php //$Id: backuplib.php,v 1.1 2009/04/27 17:44:51 adelamarre Exp $
    //This php script contains all the stuff to backup/restore
    //ezproxy mods

    //This is the "graphical" structure of the ezproxy mod:
    //
    //                       ezproxy
    //                     (CL,pk->id)
    //
    // Meaning: pk->primary key field of the table
    //          fk->foreign key to link with parent
    //          nt->nested field (recursive data)
    //          CL->course level info
    //          UL->user level info
    //          files->table may have files)
    //
    //-----------------------------------------------------------

    //This function executes all the backup procedure about this mod
    function ezproxy_backup_mods($bf,$preferences) {
        global $CFG;

        $status = true; 

        ////Iterate over ezproxy table
        if ($ezproxys = get_records ("ezproxy","course", $preferences->backup_course,"id")) {
            foreach ($ezproxys as $ezproxy) {
                if (backup_mod_selected($preferences,'ezproxy',$ezproxy->id)) {
                    $status = ezproxy_backup_one_mod($bf,$preferences,$ezproxy);
                }
            }
        }
        return $status;
    }
   
    function ezproxy_backup_one_mod($bf,$preferences,$ezproxy) {

        global $CFG;
    
        if (is_numeric($ezproxy)) {
            $ezproxy = get_record('ezproxy','id',$ezproxy);
        }
    
        $status = true;

        //Start mod
        fwrite ($bf,start_tag("MOD",3,true));
        //Print assignment data
        fwrite ($bf,full_tag("ID",4,false,$ezproxy->id));
        fwrite ($bf,full_tag("MODTYPE",4,false,"ezproxy"));
        fwrite ($bf,full_tag("NAME",4,false,$ezproxy->name));
        fwrite ($bf,full_tag("SERVERURL",4,false,$ezproxy->serverurl));
        fwrite ($bf,full_tag("TIMEMODIFIED",4,false,$ezproxy->timemodified));

        //End mod
        $status = fwrite ($bf,end_tag("MOD",3,true));

        return $status;
    }

    ////Return an array of info (name,value)
    function ezproxy_check_backup_mods($course,$user_data=false,$backup_unique_code,$instances=null) {
        if (!empty($instances) && is_array($instances) && count($instances)) {
            $info = array();
            foreach ($instances as $id => $instance) {
                $info += ezproxy_check_backup_mods_instances($instance,$backup_unique_code);
            }
            return $info;
        }
        
         //First the course data
         $info[0][0] = get_string("modulenameplural","ezproxy");
         $info[0][1] = count_records("ezproxy", "course", "$course");
         return $info;
    } 

    ////Return an array of info (name,value)
    function ezproxy_check_backup_mods_instances($instance,$backup_unique_code) {
         //First the course data
        $info[$instance->id.'0'][0] = '<b>'.$instance->name.'</b>';
        $info[$instance->id.'0'][1] = '';
        return $info;
    }

?>
