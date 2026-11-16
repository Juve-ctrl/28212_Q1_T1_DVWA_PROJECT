# AUCA Linux Administration Final Examination
## Question 1 — Task 1: DVWA Lab Evidence Index

- Student ID: 28212
- Target URL: http://task1.28212.linux-f26/
- Kali hostname: kali-28212
- Target hostname: dvwa-28212
- Database: db_28212
- Database user: jkalema
- Hardened application URL: http://127.0.0.1:4280/
- Vulnerable DVWA source commit: a1978a2fed195acd093010af6395f86a281537ca
- Recorded source commit date: 2026-10-18T18:11:33+01:00

## 1. Remediation reports

- [PRESENT] Brute Force: `05_REMEDIATION_EVIDENCE/Brute_Force/Brute_Force_remediation_notes.txt`
- [PRESENT] Command Injection: `05_REMEDIATION_EVIDENCE/Command_Injection/Command_Injection_remediation_notes.txt`
- [PRESENT] CSRF: `05_REMEDIATION_EVIDENCE/CSRF/CSRF_remediation_notes.txt`
- [PRESENT] File Inclusion: `05_REMEDIATION_EVIDENCE/File_Inclusion/File_Inclusion_remediation_notes.txt`
- [PRESENT] File Upload: `05_REMEDIATION_EVIDENCE/File_Upload/File_Upload_remediation_notes.txt`
- [PRESENT] Insecure CAPTCHA: `05_REMEDIATION_EVIDENCE/CAPTCHA/CAPTCHA_remediation_notes.txt`
- [PRESENT] SQL Injection: `05_REMEDIATION_EVIDENCE/SQL_Injection/SQL_Injection_remediation_notes.txt`
- [PRESENT] SQL Injection (Blind): `05_REMEDIATION_EVIDENCE/SQL_Injection_Blind/SQL_Injection_Blind_remediation_notes.txt`
- [PRESENT] Weak Session IDs: `05_REMEDIATION_EVIDENCE/Weak_Session_IDs/Weak_Session_IDs_remediation_notes.txt`
- [PRESENT] DOM XSS: `05_REMEDIATION_EVIDENCE/DOM_XSS/DOM_XSS_remediation_notes.txt`
- [PRESENT] Reflected XSS: `05_REMEDIATION_EVIDENCE/Reflected_XSS/Reflected_XSS_remediation_notes.txt`
- [PRESENT] Stored XSS: `05_REMEDIATION_EVIDENCE/Stored_XSS/Stored_XSS_remediation_notes.txt`
- [PRESENT] CSP Bypass: `05_REMEDIATION_EVIDENCE/CSP_Bypass/CSP_Bypass_remediation_notes.txt`
- [PRESENT] JavaScript Attacks: `05_REMEDIATION_EVIDENCE/JavaScript_Attacks/JavaScript_Attacks_remediation_notes.txt`
- [PRESENT] Authorisation Bypass: `05_REMEDIATION_EVIDENCE/Authorisation_Bypass/Authorisation_Bypass_remediation_notes.txt`
- [PRESENT] Broken Access Control: `05_REMEDIATION_EVIDENCE/Broken_Access_Control/Broken_Access_Control_remediation_notes.txt`
- [PRESENT] Open HTTP Redirect: `05_REMEDIATION_EVIDENCE/Open_HTTP_Redirect/Open_HTTP_Redirect_remediation_notes.txt`
- [PRESENT] Cryptography: `05_REMEDIATION_EVIDENCE/Cryptography/Cryptography_remediation_notes.txt`
- [PRESENT] API Security: `05_REMEDIATION_EVIDENCE/API_Security/API_Security_Remediation_Report.txt`

## 2. Original vulnerability assessment reports

All 19 original assessment reports are present and non-empty. The detailed CAPTCHA report is used as the primary CAPTCHA assessment.

- [PRESENT] `04_VULNERABILITY_ASSESSMENT/Brute_Force_assessment_notes.txt`
- [PRESENT] `04_VULNERABILITY_ASSESSMENT/Command_Injection_assessment_notes.txt`
- [PRESENT] `04_VULNERABILITY_ASSESSMENT/CSRF/CSRF_assessment_notes.txt`
- [PRESENT] `04_VULNERABILITY_ASSESSMENT/File_Inclusion/file_inclusion_assessment_notes.txt`
- [PRESENT] `04_VULNERABILITY_ASSESSMENT/File_Upload/file_upload_assessment_notes.txt`
- [PRESENT] `04_VULNERABILITY_ASSESSMENT/CAPTCHA/CAPTCHA_assessment_notes.txt`
- [PRESENT] `04_VULNERABILITY_ASSESSMENT/SQL_Injection_assessment_notes.txt`
- [PRESENT] `04_VULNERABILITY_ASSESSMENT/SQL_Injection_Blind_assessment_notes.txt`
- [PRESENT] `04_VULNERABILITY_ASSESSMENT/Weak_Session_IDs_assessment_notes.txt`
- [PRESENT] `04_VULNERABILITY_ASSESSMENT/DOM_XSS_assessment_notes.txt`
- [PRESENT] `04_VULNERABILITY_ASSESSMENT/Reflected_XSS_assessment_notes.txt`
- [PRESENT] `04_VULNERABILITY_ASSESSMENT/Stored_XSS_assessment_notes.txt`
- [PRESENT] `04_VULNERABILITY_ASSESSMENT/CSP_Bypass_assessment_notes.txt`
- [PRESENT] `04_VULNERABILITY_ASSESSMENT/JavaScript_Attacks_assessment_notes.txt`
- [PRESENT] `04_VULNERABILITY_ASSESSMENT/Authorisation_Bypass_assessment_notes.txt`
- [PRESENT] `04_VULNERABILITY_ASSESSMENT/Broken_Access_Control_assessment_notes.txt`
- [PRESENT] `04_VULNERABILITY_ASSESSMENT/Open_HTTP_Redirect_assessment_notes.txt`
- [PRESENT] `04_VULNERABILITY_ASSESSMENT/Cryptography_assessment_notes.txt`
- [PRESENT] `04_VULNERABILITY_ASSESSMENT/API_Security_assessment_notes.txt`
## 3. Hardened screenshots

Unique SS_H screenshot filenames found: 36

- `SS_H02_Hardened_DVWA_Operational.png` — `06_SCREENSHOTS_AND_LOGS/SS_H02_Hardened_DVWA_Operational.png`
- `SS_H03_Hardened_Student_Login.png` — `06_SCREENSHOTS_AND_LOGS/SS_H03_Hardened_Student_Login.png`
- `SS_H04_Brute_Force_Five_Attempts_Lockout.png` — `06_SCREENSHOTS_AND_LOGS/SS_H04_Brute_Force_Five_Attempts_Lockout.png`
- `SS_H04_Brute_Force_Hardened_Impossible_Before_Test.png` — `06_SCREENSHOTS_AND_LOGS/SS_H04_Brute_Force_Hardened_Impossible_Before_Test.png`
- `SS_H05_Brute_Force_Active_Lockout.png` — `06_SCREENSHOTS_AND_LOGS/SS_H05_Brute_Force_Active_Lockout.png`
- `SS_H06_Brute_Force_Successful_Login.png` — `06_SCREENSHOTS_AND_LOGS/SS_H06_Brute_Force_Successful_Login.png`
- `SS_H07_Command_Injection_Hardened_Normal_Ping.png` — `06_SCREENSHOTS_AND_LOGS/SS_H07_Command_Injection_Hardened_Normal_Ping.png`
- `SS_H08_Command_Injection_Hardened_Injection_Rejected.png` — `06_SCREENSHOTS_AND_LOGS/SS_H08_Command_Injection_Hardened_Injection_Rejected.png`
- `SS_H10_CSRF_Hardened_Valid_Post_Success.png` — `06_SCREENSHOTS_AND_LOGS/SS_H10_CSRF_Hardened_Valid_Post_Success.png`
- `SS_H11_CSRF_Hardened_Invalid_Token_Rejected.png` — `06_SCREENSHOTS_AND_LOGS/SS_H11_CSRF_Hardened_Invalid_Token_Rejected.png`
- `SS_H12_File_Inclusion_Hardened_Legitimate_Access.png` — `06_SCREENSHOTS_AND_LOGS/SS_H12_File_Inclusion_Hardened_Legitimate_Access.png`
- `SS_H13_File_Inclusion_Hardened_Traversal_Rejected.png` — `06_SCREENSHOTS_AND_LOGS/SS_H13_File_Inclusion_Hardened_Traversal_Rejected.png`
- `SS_H14_File_Upload_Hardened_Valid_PNG_Accepted.png` — `06_SCREENSHOTS_AND_LOGS/SS_H14_File_Upload_Hardened_Valid_PNG_Accepted.png`
- `SS_H15_File_Upload_Hardened_Fake_PNG_Rejected.png` — `06_SCREENSHOTS_AND_LOGS/SS_H15_File_Upload_Hardened_Fake_PNG_Rejected.png`
- `SS_H17_SQL_Injection_Hardened_Legitimate_Lookup.png` — `06_SCREENSHOTS_AND_LOGS/SS_H17_SQL_Injection_Hardened_Legitimate_Lookup.png`
- `SS_H18_SQL_Injection_Hardened_Payload_Rejected.png` — `06_SCREENSHOTS_AND_LOGS/SS_H18_SQL_Injection_Hardened_Payload_Rejected.png`
- `SS_H19_Blind_SQL_Injection_Hardened_Legitimate_Lookup.png` — `06_SCREENSHOTS_AND_LOGS/SS_H19_Blind_SQL_Injection_Hardened_Legitimate_Lookup.png`
- `SS_H20_Blind_SQL_Injection_Hardened_Payload_Rejected.png` — `06_SCREENSHOTS_AND_LOGS/SS_H20_Blind_SQL_Injection_Hardened_Payload_Rejected.png`
- `SS_H21_Weak_Session_IDs_Hardened_HTTP_Rejected.png` — `06_SCREENSHOTS_AND_LOGS/SS_H21_Weak_Session_IDs_Hardened_HTTP_Rejected.png`
- `SS_H22_DOM_XSS_Hardened_Payload_Displayed_Safely.png` — `06_SCREENSHOTS_AND_LOGS/SS_H22_DOM_XSS_Hardened_Payload_Displayed_Safely.png`
- `SS_H23_Reflected_XSS_Hardened_Payload_Rejected.png` — `06_SCREENSHOTS_AND_LOGS/SS_H23_Reflected_XSS_Hardened_Payload_Rejected.png`
- `SS_H24_Stored_XSS_Hardened_Payload_Displayed_Safely.png` — `06_SCREENSHOTS_AND_LOGS/SS_H24_Stored_XSS_Hardened_Payload_Displayed_Safely.png`
- `SS_H25_CSP_Hardened_Legitimate_Calculation.png` — `06_SCREENSHOTS_AND_LOGS/SS_H25_CSP_Hardened_Legitimate_Calculation.png`
- `SS_H26_CSP_Hardened_JSONP_Callback_Rejected.png` — `06_SCREENSHOTS_AND_LOGS/SS_H26_CSP_Hardened_JSONP_Callback_Rejected.png`
- `SS_H27_Authorisation_Bypass_Hardened_Data_Access_Denied.png` — `06_SCREENSHOTS_AND_LOGS/SS_H27_Authorisation_Bypass_Hardened_Data_Access_Denied.png`
- `SS_H28_Authorisation_Bypass_Hardened_Update_Denied.png` — `06_SCREENSHOTS_AND_LOGS/SS_H28_Authorisation_Bypass_Hardened_Update_Denied.png`
- `SS_H29_Broken_Access_Control_Hardened_Own_Profile_Allowed.png` — `06_SCREENSHOTS_AND_LOGS/SS_H29_Broken_Access_Control_Hardened_Own_Profile_Allowed.png`
- `SS_H30_Broken_Access_Control_Hardened_Other_Profile_Denied.png` — `06_SCREENSHOTS_AND_LOGS/SS_H30_Broken_Access_Control_Hardened_Other_Profile_Denied.png`
- `SS_H31_Open_Redirect_Hardened_Local_Destination_Allowed.png` — `06_SCREENSHOTS_AND_LOGS/SS_H31_Open_Redirect_Hardened_Local_Destination_Allowed.png`
- `SS_H32_Open_Redirect_Hardened_External_URL_Rejected.png` — `06_SCREENSHOTS_AND_LOGS/SS_H32_Open_Redirect_Hardened_External_URL_Rejected.png`
- `SS_H33_Cryptography_Hardened_Valid_Token_Accepted.png` — `06_SCREENSHOTS_AND_LOGS/SS_H33_Cryptography_Hardened_Valid_Token_Accepted.png`
- `SS_H34_Cryptography_Hardened_Tampered_Token_Rejected.png` — `06_SCREENSHOTS_AND_LOGS/SS_H34_Cryptography_Hardened_Tampered_Token_Rejected.png`
- `SS_H35_API_Security_Password_Hashes_Not_Exposed.png` — `06_SCREENSHOTS_AND_LOGS/SS_H35_API_Security_Password_Hashes_Not_Exposed.png`
- `SS_H36_API_Security_Mass_Assignment_Rejected.png` — `06_SCREENSHOTS_AND_LOGS/SS_H36_API_Security_Mass_Assignment_Rejected.png`
- `SS_H37_API_Security_Command_Injection_Rejected.png` — `06_SCREENSHOTS_AND_LOGS/SS_H37_API_Security_Command_Injection_Rejected.png`
- `SS_H38_API_Security_OAuth_Bearer_Token_Accepted.png` — `06_SCREENSHOTS_AND_LOGS/SS_H38_API_Security_OAuth_Bearer_Token_Accepted.png`

### Screenshot sequence gaps

- `SS_H09` was not found in the two audited screenshot folders.
- `SS_H16` was not found in the two audited screenshot folders.
- These gaps are recorded rather than represented as captured evidence.

## 4. Required video recordings

Required format: MP4, minimum 720p, with continuous webcam face visibility and narration.
Show the student identity and dynamic terminal prompt at the beginning of each recording.

- [ ] `28212_Q1_T1_00_Lab_Setup.mp4`
- [ ] `28212_Q1_T1_01_Brute_Force.mp4`
- [ ] `28212_Q1_T1_02_Command_Injection.mp4`
- [ ] `28212_Q1_T1_03_CSRF.mp4`
- [ ] `28212_Q1_T1_04_File_Inclusion.mp4`
- [ ] `28212_Q1_T1_05_File_Upload.mp4`
- [ ] `28212_Q1_T1_06_Insecure_CAPTCHA.mp4`
- [ ] `28212_Q1_T1_07_SQL_Injection.mp4`
- [ ] `28212_Q1_T1_08_SQL_Injection_Blind.mp4`
- [ ] `28212_Q1_T1_09_Weak_Session_IDs.mp4`
- [ ] `28212_Q1_T1_10_DOM_XSS.mp4`
- [ ] `28212_Q1_T1_11_XSS_Reflected.mp4`
- [ ] `28212_Q1_T1_12_XSS_Stored.mp4`
- [ ] `28212_Q1_T1_13_CSP_Bypass.mp4`
- [ ] `28212_Q1_T1_14_JavaScript.mp4`
- [ ] `28212_Q1_T1_15_Authorisation_Bypass.mp4`
- [ ] `28212_Q1_T1_16_Broken_Access_Control.mp4`
- [ ] `28212_Q1_T1_17_Open_HTTP_Redirect.mp4`
- [ ] `28212_Q1_T1_18_Cryptography.mp4`
- [ ] `28212_Q1_T1_19_API_Security.mp4`

Videos found during the initial inventory: 0.

## 5. Environment and submission limitations

- The lab was implemented using Docker containers on macOS with Docker Desktop.
- Docker containers are not equivalent to two separate full virtual machines.
- The vulnerable DVWA is published on 127.0.0.1:80 and the hardened DVWA on 127.0.0.1:4280; neither application port is bound to all host interfaces.
- Each environment has an Internal=true Docker bridge network for application/database communication and a separate Internal=false access bridge. These access bridges are not air-gapped; the lab is not described as fully isolated from external networking.
- The running prebuilt DVWA image must not be represented as having been built from the separately recorded Git source commit.
- Remote File Inclusion was not tested because of the installed PHP runtime limitation.
- Brute Force remediation retains an MD5 password-storage limitation; the lockout remediation does not make the complete authentication system production-ready.
- Screenshot inventory counts indicate files found, not proof that every assignment evidence requirement is satisfied.

## 6. Final submission checks

- [ ] Confirm lecturer acceptance of the Docker-based lab in place of two separate VMs.
- [ ] Complete and review all 20 MP4 recordings.
- [ ] Verify video filenames, playback, resolution, narration and continuous webcam visibility.
- [ ] Complete the final technical and evidence review.
- [ ] Upload the videos and evidence index to the approved submission location.
- [ ] Set sharing permissions as required and test every submitted link.

### Screenshot sequence notes

- `SS_H09`: No separate screenshot with this filename is present. The hardened Command Injection evidence is represented by `SS_H07` (normal ping) and `SS_H08` (injection rejected). The baseline invalid-input evidence is `SS29_Command_Injection_Impossible_Invalid_Input.png`.
- `SS_H16`: No separate screenshot with this filename is present. The hardened File Upload evidence is represented by `SS_H14` (valid PNG accepted) and `SS_H15` (fake PNG rejected).
- These sequence gaps are disclosed rather than filled with renamed or unrelated screenshots.

End of evidence index.
