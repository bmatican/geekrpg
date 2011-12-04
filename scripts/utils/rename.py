#! /usr/bin/env python

import os
import shutil
import sys
import re

##############################################################################
def usage():
  print sys.argv[0] + " <path> <searchfor> <replaceby>"
  sys.exit()
##############################################################################

dirname = '.'
if len(sys.argv) == 4:
  dirname = sys.argv[1]
  searchfor = sys.argv[2]
  replaceby = sys.argv[3]
else:
  usage()

dirname = os.path.abspath(dirname)
content = os.listdir(dirname)
for f in content:
  p = os.path.join(dirname, f)
  if os.path.isfile(p):
    d = os.path.dirname(p)
    m = p.split('.')
    m[1] = m[1].replace(replaceby, "").lower()
    if len(m) > 0:
      newfile = d + "/" + replaceby + "." + m[1] + ".php"
      print p + "  -->  "  + newfile
      # os.system("git mv " + p + " " + newfile)
