#Region ;**** Directives created by AutoIt3Wrapper_GUI ****
#AutoIt3Wrapper_Icon=C:\Icons\s.ico
#AutoIt3Wrapper_Outfile=wolcheck.exe
#EndRegion ;**** Directives created by AutoIt3Wrapper_GUI ****
#include "D:\pydio\autoit\Include\mysql_lib.au3"
#include <Array.au3>

While 1
   $sql = _MySQLConnect("root","kMCLmqiYiSOS6sE6I4pJ","wolcheck","10.0.0.4", "{MySQL ODBC 3.51 Driver}", 3307)
   $var = _Query($sql,"SELECT * FROM vm WHERE state != newState")
   Dim $vms[0][2]
   With $var
   While NOT .EOF
	  $length = UBound($vms)
	  ReDim $vms[$length + 1][2]
	  $vms[$length][0] = .Fields("name").value
	  $vms[$length][1] = .Fields("newState").value
   .MoveNext
   WEnd
   EndWith

   For $i = 0 To UBound($vms, 1) - 1
	  If $vms[$i][1] = "1" Then
		 RunWait('"D:\Program Files\Oracle\VirtualBox\VBoxManage.exe" startvm ' & $vms[$i][0] & " --type headless", "", @SW_HIDE)
	  ElseIf $vms[$i][1] = "2" Then
		 RunWait('"D:\Program Files\Oracle\VirtualBox\VBoxManage.exe" controlvm ' & $vms[$i][0] & " acpipowerbutton", "", @SW_HIDE)
	  Else
		 ContinueLoop
	 EndIf
	 $sql.execute("UPDATE wolcheck.vm SET state = " & $vms[$i][1] & " WHERE name = '" & $vms[$i][0] & "'")
   Next
   _MySQLEnd($sql)

   Sleep(5000)

WEnd