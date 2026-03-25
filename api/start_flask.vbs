Set WshShell = CreateObject("WScript.Shell")
WshShell.Run Chr(34) & "C:\Users\ASUS\AppData\Local\Programs\Python\Python311\python.exe" & Chr(34) & " C:\laragon\www\smarthealth_app\api\predict.py >> C:\laragon\www\smarthealth_app\api\flask.log 2>&1", 0, False
