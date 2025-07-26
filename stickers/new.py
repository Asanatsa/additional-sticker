import json as j
from pathlib import *
import sys


if len(sys.argv) == 1:
    sys.exit(1)


base_dir = Path(sys.argv[1])
print(base_dir)

files = base_dir.glob("*.{png,jpg,jpeg,gif,webp}")

l = []
for f in files:
    l.append({"name":"", "id":f.stem, "src":f.name})

o = {"name":"", "id":base_dir.stem, "author":"", "description":"", "icon":"","url":"", "copyright": "", "stickers":l}

print(j.dumps(o, indent = 4))
Path(base_dir, "info.json").write_text(j.dumps(o, indent = 4), "UTF-8")