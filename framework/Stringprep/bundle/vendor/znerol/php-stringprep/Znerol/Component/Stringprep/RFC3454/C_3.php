<?php
/* This file is automatically generated.  DO NOT EDIT!
   Instead, edit gen-stringprep-tables.pl and re-run.  */

namespace Znerol\Component\Stringprep\RFC3454;
class C_3 {
  public static function filter($cp) {
    if ($cp >= 0x00E000 && $cp <= 0x00F8FF) return false;
    if ($cp >= 0x0F0000 && $cp <= 0x0FFFFD) return false;
    if ($cp >= 0x100000 && $cp <= 0x10FFFD) return false;
    return true;
  }
}
