# AUCA Linux Administration Final Examination
## Question 1 — Task 1: DVWA Vulnerability Assessment and Remediation

**Student ID:** 28212  
**Project:** DVWA Testing Environment and Security Hardening  
**Assessment date:** November 2026

## 1. Executive Summary

This project established a controlled DVWA testing environment, assessed the application's security across 19 vulnerability categories, documented the findings, implemented security controls in a separate hardened environment, and recorded evidence of the remediation work.

The vulnerable baseline and hardened implementation were maintained as separate Docker-based environments. Original assessment reports, remediation reports, screenshots, and supporting records are organised in the project directory and referenced by the evidence index.

## 2. Lab Architecture

The lab was implemented on macOS using Docker Desktop and ARM64 containers.

The environment includes:
- Kali Linux testing workstation: `kali-28212`.
- Vulnerable DVWA target: `dvwa-28212`.
- Hardened DVWA application: `dvwa-28212-hardened`.
- Separate MariaDB database containers and persistent database volumes for the baseline and hardened environments.
- Separate Docker bridge networks for the two environments.

The vulnerable application is published on `127.0.0.1:80`, while the hardened application is published on `127.0.0.1:4280`. These bindings restrict host-side publication to the Mac loopback interface.

Each environment has an internal Docker bridge network and a separate non-internal access bridge. The access bridges are not air-gapped.

## 3. Baseline Identification and Preservation

The baseline DVWA source snapshot was recorded with the following Git information:

- Commit: `a1978a2fed195acd093010af6395f86a281537ca`
- Commit date: `2026-10-18T18:11:33+01:00`

The running vulnerable target used the prebuilt image `ghcr.io/digininja/dvwa:latest`. The separately recorded Git source snapshot must not be represented as the source used to build that prebuilt image.

A MariaDB SQL backup of `db_28212` was preserved at:

`02_VULNERABLE_BASELINE/db_28212_baseline_20261108.sql`

Its SHA-256 checksum is:

`04e25a728a1fba7692c2ed01b6b11919d3c6dfab2ef0bae702b8752b3331d0a5`

The checksum provides a reference for detecting subsequent file changes; it does not by itself demonstrate a successful database restoration.

## 4. Vulnerability Assessment and Remediation

The assessment covered these 19 categories:

1. Brute Force
2. Command Injection
3. Cross-Site Request Forgery (CSRF)
4. File Inclusion
5. File Upload
6. Insecure CAPTCHA
7. SQL Injection
8. SQL Injection (Blind)
9. Weak Session IDs
10. DOM-based Cross-Site Scripting
11. Reflected Cross-Site Scripting
12. Stored Cross-Site Scripting
13. Content Security Policy (CSP) Bypass
14. JavaScript Attacks
15. Authorisation Bypass
16. Broken Access Control
17. Open HTTP Redirect
18. Cryptography
19. API Security

Individual assessment notes and remediation reports are maintained in:

- `04_VULNERABILITY_ASSESSMENT/`
- `05_REMEDIATION_EVIDENCE/`

Hardened verification screenshots are maintained in `06_SCREENSHOTS_AND_LOGS/`. The evidence index identifies the available screenshots and discloses the H09 and H16 sequence gaps.

## 5. Verification Approach

The hardened environment was checked using targeted legitimate-operation and rejection cases. Examples include:

- Normal ping functionality and rejection of command-injection input.
- Acceptance of a valid PNG upload and rejection of a disguised invalid image.
- Acceptance of legitimate SQL lookups and rejection of injection payloads.
- Rejection of invalid CSRF tokens.
- Denial of unauthorised profile or data access.
- Safe handling of cross-site scripting payloads.
- API responses that do not expose password hashes and rejection of selected mass-assignment and command-injection inputs.
- OAuth bearer-token authentication and access to a protected API endpoint.

These checks support the specific results documented in the individual reports. They do not establish that every possible vulnerability or API endpoint has been exhaustively tested.

## 6. Known Limitations

- The lab uses Docker containers on macOS, not two separate full virtual machines. Lecturer acceptance of this substitution must be confirmed.
- The prebuilt vulnerable DVWA image and separately recorded Git source snapshot are distinct artefacts.
- Docker access bridges are not air-gapped, although the application ports are published only on loopback.
- Remote File Inclusion was not tested because of the installed PHP runtime limitation.
- The Brute Force remediation retains an MD5 password-storage limitation and is not a production-ready authentication system.
- Screenshot sequence entries H09 and H16 are missing as separate files; the available related evidence is identified in the evidence index.

## 7. Evidence and Submission

The accompanying `28212_Q1_T1_Evidence_Index.md` identifies the assessment reports, remediation reports, screenshots, video requirements, environment limitations, and final submission checks.

Twenty separate video recordings are planned: one lab-setup recording and one recording for each of the 19 vulnerability categories. These recordings remain outstanding until completed, reviewed, and uploaded.

## 8. Conclusion

The project produced separate vulnerable and hardened DVWA environments, documented assessments across 19 vulnerability categories, implemented and documented remediation controls, and retained supporting screenshots and a baseline database backup.

The final submission must include the completed video recordings and should retain the disclosed technical limitations. The Docker-based implementation should not be described as two separate virtual machines.
