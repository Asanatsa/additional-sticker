import json as j
from pathlib import *

base_dir = Path.cwd()
print(base_dir)

files = base_dir.glob("*.png")

l = []
for f in files:
    l.append({"name":"", "id":f.stem, "src":f.name})

o = {"name":"", "id":"", "author":"", "notice":"", "stickers":l}

print(j.dumps(o, indent = 4))
Path(base_dir, "info.json").write_text(j.dumps(o, indent = 4), "UTF-8")