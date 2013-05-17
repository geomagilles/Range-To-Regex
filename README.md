
Create a regex that matches an Integer range
============================================

This is a PHP implementation of https://github.com/dimka665/range-regex.

It's not yet optimized (regex could be shorter) but it works.



Usage
------
    
    regex_for_range(12, 34)

generates ``"1[2-9]|2\d|3[0-4]"``
  
    regex_for_range_complete(1368022487, 1368540887)
    
generates ``"136802248[7-9]|136802249\d|1368022[5-9]\d{2}|136802[3-9]\d{3}|13680[3-9]\d{4}|1368[1-4]\d{5}|13685[0-3]\d{4}|1368540[0-7]\d{2}|13685408[0-7]\d|136854088[0-7]"``
