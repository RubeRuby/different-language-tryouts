# This is a part of a GUI that extracts an .rpa archive of your choosing. It can also compile them again.
# I wrote this because I had to manually extract the archives using commands and after a time this became bothersome.
# This is written using the Tkinter library which is a library used for writing GUI's in Python.
# Note that some variables and functions will be undefined since this is not the full code.

def open_file(type):
    global file_path_dir
    global full_path_dir
    global files

    if type == "file":
        file_path_dir = askopenfilename()
        file_path_dir = '"' + file_path_dir + '"'
        file_path.set(file_path_dir)

    elif type == "path":
        full_path_dir = askdirectory()
        full_path_dir = '"' + full_path_dir + '"'
        full_path.set(full_path_dir)

    if type == "files":
        files = askopenfilenames(parent=root, title='Choose a file')
        print files_path.set(str(files)[1:-1])


def decompiledec():
    os.system('python unrpa.py' + " " + "-p " + full_path_dir + " " + file_path_dir)


def compiledec():
    files_checked = '"' + '" "'.join(map(str, (files))) + '"'
    splits = files_checked.split(" ")
    files_checked = ""
    for split in splits:
        temp = os.path.basename(split)
        temp = temp[:-1] + "=" + split
        files_checked = files_checked + temp + " "
    print 'python rpatool.py -c ' + full_path_dir[:-1] + "/archive.rpa" + '" ' + files_checked
    os.system('python rpatool.py -c ' + full_path_dir[:-1] + "/archive.rpa" + '" ' + files_checked)


Button(f1, text="Browse", command=lambda: open_file("file")).grid(row=0, column=27, sticky='ew', padx=8, pady=4)
Button(f2, text="Browse", command=lambda: open_file("path")).grid(row=0, column=27, sticky='ew', padx=8, pady=4)
Button(f3, text="Browse", command=lambda: open_file("files")).grid(row=0, column=27, sticky='ew', padx=8, pady=4)
Button(f4, text="Extract", command=decompiledec, width=8).grid(row=0, column=27, sticky='ew', padx=(175, 8), pady=4)
Button(f4, text="Archive", command=compiledec, width=8).grid(row=0, column=28, sticky='ew', padx=(0, 8), pady=4)