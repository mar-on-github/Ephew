# Missing a file?
<font color=red>The contents of this folder will soon be replaced by Symfony secrets</font>
The files in this folder are ignored for safekeeping, but you can replicate your own version of them safely yourself!
<hr>
## sqlpassword.php
```php
<?php
function GetSQLCreds(string $output = 'username'|'password'): string
{
    $username = "`Your sql server username`";
    $password = "`Your sql server password`";
    return $$output;
}
?>
```
