import sys
import json as j
import zipfile as zipf
from pathlib import *

ids = []


def input_id():
    gid = input("ID（15个字符以内，只允许大小写字母和数字） * : ")
    if len(gid) <= 15 and gid.isalnum():
        return gid
    else:
        print("ID必须是15个字符以内的字母或数字")
        return input_id()
    

def input_sticker_id():
    sid = input(f"表情的ID（10个字符以内，只允许大小写字母和数字） * : ")
    if len(sid) <= 10 and sid.isalnum() and sid not in ids:
        return sid
    elif sid in ids:
        print("ID已存在，请使用其他ID")
        return input_sticker_id()
    else:
        print("ID必须是10个字符以内的字母或数字")
        return input_sticker_id()
    

def input_icon(base_dir):
    icon = input("图标文件 * : ")
    icon_path = base_dir / icon
    if icon_path.exists() and icon_path.is_file():
        return icon
    else:
        print(f"文件不存在或无效: {icon}")
        return input_icon(base_dir)

def main(dir_path):

    base_dir = Path(dir_path)
    print(base_dir)

    extensions = ("*.png", "*.jpg", "*.gif", "*.jpeg", "*.webp") 
    files = []
    for e in extensions:
        files += list(base_dir.glob(e))

    if len(files) == 0:
        print("无匹配的图片文件")
        sys.exit(1)

    print("请填写以下信息，星号为必填项")

    group_name = input("名称 * : ")
    group_id = input_id()        
    author = input("作者 （可选） : ")
    description = input("描述（可选）: ")
    icon = input_icon(base_dir)
    url = input("链接（可选）: ")
    copyright_info = input("版权信息（可选）: ")


    edit = input("是否继续编辑各个表情的信息？(y/n) ").lower() == 'y'


    l = []
    for f in files:
        if edit:
            print("\n")
            name = input(f"表情 '{f.name}' 的名称（可选）: ")
            sticker_id = input_sticker_id()
            l.append({"name": name, "id": sticker_id, "src": f.name})

        else:
            l.append({"name":"", "id":f.stem, "src":f.name})

    o = {"name":group_name, 
        "id":group_id, 
        "author":author,
        "description":description, 
        "icon":icon,
        "url":url, 
        "copyright": copyright_info,
        "stickers":l
        }

    print(j.dumps(o, ensure_ascii=False, indent = 4))
    Path(base_dir, "info.json").write_text(j.dumps(o, ensure_ascii=False, encoding="UTF-8", indent = 4), "UTF-8")
    input("\n你现在可以编辑info.json文件，完成后按回车继续。")



    with zipf.ZipFile(base_dir / f"{base_dir.stem}.spck", 'w') as z:
        for f in files:
            z.write(f, arcname=f.name)
        z.write(base_dir / "info.json", arcname="info.json")

    print(f"文件已创建：{base_dir / f'{base_dir.stem}.spck'}")






if __name__ == "__main__":

    if len(sys.argv) == 1:
        print("请提供表情目录作为参数")
        sys.exit(1)

    main(sys.argv[1])