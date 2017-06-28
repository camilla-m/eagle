<?php

mysqli_report(MYSQLI_REPORT_STRICT);

function open_database() 
{
    try {
        $conn = new mysqli(localhost/phpmyadmin, eagle_user, eagle_user, eagle_user);
        return $conn;
    } catch (Exception $e) {
        echo $e->getMessage();
        return null;
    }
}

function close_database($conn) 
{
    try {
        mysqli_close($conn);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

function find( $table = null, $id = null ) 
{  
    $database = open_database();
    $found = null;
    try {
        if ($id) {
            $sql = "SELECT * FROM " . $table . " WHERE id = " . $id;
            $result = $database->query($sql);

            if ($result->num_rows > 0) {
                $found = $result->fetch_assoc();
            }
        } else {
            $sql = "SELECT * FROM " . $table;
            $result = $database->query($sql);

            if ($result->num_rows > 0) {
                $numRows = $result->num_rows;
                for ($i=0; $i < $numRows; $i++) { 
                    $found[$i] = $result->fetch_assoc();
                }
            }
        }
    } catch (Exception $e) {
        $_SESSION['message'] = $e->GetMessage();
        $_SESSION['type'] = 'danger';
    }

    close_database($database);
    
    return $found;
}

function find_all( $table ) 
{
    return find($table);
}

/*
 * Preencha as funções abaixo;
*/

function save($table = null, $data = null) 
{

    $database = open_database();
    
    try {

    $columns = implode(", ",array_keys($data));
    $escaped_values = array_map('mysql_real_escape_string', array_values($data));
        
    foreach ($escaped_values as $idx=>$dados) $escaped_values[$idx] = "'".$dados."'";
    $values  = implode(", ", $escaped_values);
    $query = "INSERT INTO $table ($columns) VALUES ($values)";
        
    $result = $database->query($query);
  
    } catch (Exception $e) {
        $_SESSION['message'] = $e->GetMessage();
        $_SESSION['type'] = 'danger';
    }

    close_database($database);
	
}

function update($table = null, $id = 0, $data = null) 
{
    $database = open_database();
    
    $sql = "UPDATE ".$table.
    " SET ";
    $i = 0;
        
    foreach($data as $key => $value) {
        $sql.= $key." = '".$value."'";
        if ($i < count($data) - 1) {
            $sql.= " , ";
        }
        $i++;
    }
    if (id > 0) {
        $sql.= " WHERE id = ". $id;

        }
    
    $result = $database->query($sql);

    close_database($database);
        
}

function remove( $table = null, $id = null ) 
{
    $database = open_database();

    
    try {
        if ($id > 0) {
            $sql = "DELETE FROM " . $table . " WHERE id = " . $id;
            $result = $database->query($sql);
            
    } 
    }
    catch (Exception $e) {
        $_SESSION['message'] = $e->GetMessage();
        $_SESSION['type'] = 'danger';
    }

    close_database($database); 
        
}