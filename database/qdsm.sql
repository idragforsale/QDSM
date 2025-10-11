/*
 Navicat Premium Data Transfer

 Source Server         : Localhost
 Source Server Type    : MySQL
 Source Server Version : 100510
 Source Host           : localhost:3306
 Source Schema         : qdsm

 Target Server Type    : MySQL
 Target Server Version : 100510
 File Encoding         : 65001

 Date: 11/10/2025 21:32:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for document_views
-- ----------------------------
DROP TABLE IF EXISTS `document_views`;
CREATE TABLE `document_views`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `document_id` int(11) NOT NULL,
  `view_count` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `document_id`(`document_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of document_views
-- ----------------------------
INSERT INTO `document_views` VALUES (1, 3, 49);
INSERT INTO `document_views` VALUES (2, 2, 6);
INSERT INTO `document_views` VALUES (3, 6, 36);
INSERT INTO `document_views` VALUES (4, 5, 2);
INSERT INTO `document_views` VALUES (5, 4, 1);
INSERT INTO `document_views` VALUES (6, 7, 103);
INSERT INTO `document_views` VALUES (7, 8, 4);
INSERT INTO `document_views` VALUES (8, 12, 5);
INSERT INTO `document_views` VALUES (9, 13, 1);

-- ----------------------------
-- Table structure for tb_department
-- ----------------------------
DROP TABLE IF EXISTS `tb_department`;
CREATE TABLE `tb_department`  (
  `dep_code` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `dep_name_th` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `dep_name_en` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `dep_name_short` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_department
-- ----------------------------
INSERT INTO `tb_department` VALUES ('001', 'โรงพยาบาลหนองคาย', 'Nongkhai Hospital', 'NK');
INSERT INTO `tb_department` VALUES ('002', 'องค์กรบริหารสูงสุด', 'Goveming body', 'GOV');
INSERT INTO `tb_department` VALUES ('003', 'กลุ่มงานศัลยกรรม', 'Surgical Department', 'SUR');
INSERT INTO `tb_department` VALUES ('004', 'กลุ่มงานสูติ-นรีเวชกรรม', 'Obstetric & Gynecological Department', 'OBG');
INSERT INTO `tb_department` VALUES ('005', 'งานห้องคลอด', 'Labor Room', 'LR');
INSERT INTO `tb_department` VALUES ('006', 'ห้องฝากครรภ์และวางแผนครอบครัว', 'Ante  Natal Care & Family  Planning ', 'ANC');
INSERT INTO `tb_department` VALUES ('007', 'กลุ่มงานอายุรกรรม', 'Medical Department\n \n', 'MED');
INSERT INTO `tb_department` VALUES ('008', 'กลุ่มงานกุมารเวชกรรม', 'Pediatric  Department', 'PED');
INSERT INTO `tb_department` VALUES ('009', 'กลุ่มงานจักษุวิทยา', 'Eyes   Department', 'EYE');
INSERT INTO `tb_department` VALUES ('010', 'กลุ่มงานโสต ศอ นาสิก', 'Ear  Nose  Throat  Department ', 'ENT');
INSERT INTO `tb_department` VALUES ('011', 'กลุ่มงานออร์โธปิดิกส์', 'Orthopedic  Department ', 'ORT');
INSERT INTO `tb_department` VALUES ('012', 'กลุ่มงานวิศัญญี', 'Anesthesiology  Department ', 'ANE');
INSERT INTO `tb_department` VALUES ('013', 'กลุ่มงานพยาธิวิทยาวิภาค', 'Pathological   Department ', 'PAT');
INSERT INTO `tb_department` VALUES ('014', 'กลุ่มงานเทคนิคการแพทย์และพายาธิวิทยาคลีนิก', 'Laboratory  Department ', 'LAB');
INSERT INTO `tb_department` VALUES ('015', 'กลุ่มงานรังสีวิทยา', 'Radiological   Department ', 'RAD');
INSERT INTO `tb_department` VALUES ('016', 'กลุ่มงานเวชกรรมฟื้นฟู', 'Rehabilitation   Department', 'REH');
INSERT INTO `tb_department` VALUES ('017', 'กลุ่มงานผู้ป่วยนอก', 'Out  Patient   Department', 'OPD');
INSERT INTO `tb_department` VALUES ('018', 'กลุ่มงานจิตเวช', 'Psychiatric   Department', 'PSY');
INSERT INTO `tb_department` VALUES ('019', 'กลุ่มงานอุบัติเหตุและนิติเวชวิทยา', 'Accident  &  Forensic  Medicine  Department', 'AFM');
INSERT INTO `tb_department` VALUES ('020', 'กลุ่มงานทันตกรรม', 'Dental Department ', 'DEN');
INSERT INTO `tb_department` VALUES ('021', 'กลุ่มงานเภสัชกรรม', 'Pharmaceutical   Department', 'PHA');
INSERT INTO `tb_department` VALUES ('022', 'กลุ่มงานเภสัชกรรมฝ่ายผลิต', 'Pharmaceutical   Department  Manafacturing', 'PHAM');
INSERT INTO `tb_department` VALUES ('023', 'กลุ่มงานเภสัชกรรมผู้ป่วยนอก', 'Pharmaceutical   Department  Manafacturing', 'PHAO');
INSERT INTO `tb_department` VALUES ('024', 'กลุ่มงานเภสัชกรรมผู้ป่วยใน', 'Pharmaceutical   Department  Manafacturing', 'PHAI');
INSERT INTO `tb_department` VALUES ('025', 'งานคลังเวชภัณฑ์ยา', 'Pharmacy stock', 'PHAs');
INSERT INTO `tb_department` VALUES ('026', 'กลุ่มงานเวชกรรมสังคม', 'Community   Medical  Department ', 'COM');
INSERT INTO `tb_department` VALUES ('027', 'กลุ่มงานพัฒนาระบบบริการสุขภาพ(พรส.)', 'Quality   Managing   Department ', 'QMD');
INSERT INTO `tb_department` VALUES ('028', 'งานพัฒนาคุณภาพบริการ', 'Quality   Improvement Service', 'QIS');
INSERT INTO `tb_department` VALUES ('029', 'งานผลิตและพัฒนาบุคลากร', 'Human   Resource  Development', 'HRD');
INSERT INTO `tb_department` VALUES ('030', 'งานเวชสารสนเทศ', 'Medical LibraryRecord', 'MLR');
INSERT INTO `tb_department` VALUES ('031', 'งานเวชนิทัศน์และโสตทัศน์ศึกษา', 'Audio   - Vision', 'AUD');
INSERT INTO `tb_department` VALUES ('032', 'งานห้องสมุด', 'Library  ', 'LIB');
INSERT INTO `tb_department` VALUES ('033', 'งานนโยบายและแผน', 'Strategy', 'Str');
INSERT INTO `tb_department` VALUES ('034', 'หน่วยจัดเก็บรายได้', 'Revenue collection', 'RC');
INSERT INTO `tb_department` VALUES ('035', 'ศูนย์สิทธิการรักษา', ' Eligibility right center', 'ERC');
INSERT INTO `tb_department` VALUES ('036', 'กลุ่มงานสุขศึกษาประชาสัมพันธ์', 'Health   Education   Department', 'HED');
INSERT INTO `tb_department` VALUES ('037', 'กลุ่มงานโภชนศาสตร์', 'Nutritional   Department', 'NUT');
INSERT INTO `tb_department` VALUES ('038', 'สำนักงานองค์กรพยาบาล', 'Nursing   Department ', 'NUR');
INSERT INTO `tb_department` VALUES ('039', 'งานจ่ายกลาง', 'Central  Sterile  Supply  Department', 'CSSD');
INSERT INTO `tb_department` VALUES ('040', ' งานควบคุมและป้องกันการติดเชื้อ', 'Infection  Control ', 'IC');
INSERT INTO `tb_department` VALUES ('041', 'งานอุบัติเหตุฉุกเฉิน', 'Emergency Room ', 'ER');
INSERT INTO `tb_department` VALUES ('042', 'งานผู้ป่วยหนัก 1', 'Intensive Cave Unit 1', 'ICU1');
INSERT INTO `tb_department` VALUES ('043', 'งานผู้ป่วยหนัก 2', 'Intensive Cave Unit 2', 'ICU2');
INSERT INTO `tb_department` VALUES ('044', 'งานผู้ป่วยหนักศัลยกรรมประสาท', 'Neuro Surgery ICU', 'NSICU');
INSERT INTO `tb_department` VALUES ('045', 'งานไตเทียม', 'Hemodialysis Unit', 'HEMO');
INSERT INTO `tb_department` VALUES ('046', 'งานห้องผ่าตัด', 'Labor Room', 'OR');
INSERT INTO `tb_department` VALUES ('047', 'งานห้องคลอด', 'Labor  Room', 'LR');
INSERT INTO `tb_department` VALUES ('048', 'หอผู้ป่วยศัลยกรรมชาย1', 'Male Surgery  ward 1', 'MSUR1');
INSERT INTO `tb_department` VALUES ('049', 'หอผู้ป่วยศัลยกรรมชาย2', 'Male Surgery  ward 2', 'MSUR2');
INSERT INTO `tb_department` VALUES ('050', 'หอผู้ป่วยศัลยกรรมหญิง', 'Female Surgery ward ', 'FSUR');
INSERT INTO `tb_department` VALUES ('051', 'หอผู้ป่วยศัลยกรรมกระดูก', 'Orthopedic ward', 'ORT');
INSERT INTO `tb_department` VALUES ('052', 'หอผู้ป่วยอายุรกรรมชาย1', 'Male Medical ward1', 'MMED1');
INSERT INTO `tb_department` VALUES ('053', 'หอผู้ป่วยอายุรกรรมชาย2', 'Male Medical ward2', 'MMED2');
INSERT INTO `tb_department` VALUES ('054', 'หอผู้ป่วยอายุรกรรมหญิง', 'Female Surgery ward ', 'FMED');
INSERT INTO `tb_department` VALUES ('055', 'หอผู้ป่วยอายุรกรรมรวม ', 'Total Medical ward', 'TMED');
INSERT INTO `tb_department` VALUES ('056', 'หอผู้ป่วยทารกแรกเกิดวิกฤต(กุมาร1)', 'Pediatric ward   1', 'PED1');
INSERT INTO `tb_department` VALUES ('057', 'หอผู้ป่วยกุมารเวชกรรม 2', 'Pediatric ward   2', 'PED2');
INSERT INTO `tb_department` VALUES ('058', 'ศูนย์กระตุ้นพัฒนาการเด็ก', 'Pediatric ward   3', 'PED3');
INSERT INTO `tb_department` VALUES ('059', 'หอผู้ป่วย ตา หู คอ จมูก', 'Eye  Ear Nose  Throat  ward', 'EENT');
INSERT INTO `tb_department` VALUES ('060', 'หอผู้ป่วยพิเศษ 114 เตียง (ชั้น4/5)', 'Private ward  1', 'PRI1');
INSERT INTO `tb_department` VALUES ('061', 'หอผู้แยกโรค 60 เตียง (ชั้น1)', 'Cohort 1', 'COH1');
INSERT INTO `tb_department` VALUES ('062', 'หอผู้ป่วยพิเศษ 60 เตียง (ชั้น2)', 'Private ward 2', 'PRI2');
INSERT INTO `tb_department` VALUES ('063', 'หอผู้ป่วยพิเศษ 60 เตียง  (ชั้น3)', 'Private ward 3', 'PRI3');
INSERT INTO `tb_department` VALUES ('064', 'หอผู้ป่วยแยกโรค 60 เตียง  (ชั้น4)', 'Cohort 2', 'COH2');
INSERT INTO `tb_department` VALUES ('065', 'หอผู้ป่วยพิเศษสู่ขวัญ (60เตียงชั้น5)', 'Sukwhan', 'SUK');
INSERT INTO `tb_department` VALUES ('066', 'หอผู้ป่วยพิเศษสิริปุณโณ (ชั้น10)', 'Siripunno', 'SPN');
INSERT INTO `tb_department` VALUES ('067', 'หอผู้ป่วยจิตเวช และยาเสพติด', 'Psychiatric   ward', 'PSY');
INSERT INTO `tb_department` VALUES ('068', 'หอผู้ป่วยแยกโรค สิริปุณโณ  (ชั้น8)', 'Cohort 3', 'COH3');
INSERT INTO `tb_department` VALUES ('069', 'กลุ่มงานการพยาบาลชุมชน(COC)', 'Continuing of Care Center', 'COC');
INSERT INTO `tb_department` VALUES ('070', 'กลุ่มงานการพยาบาลผู้ป่วยนอก', 'Out  Patient   Department', 'OPD');
INSERT INTO `tb_department` VALUES ('071', 'ห้องฝากครรภ์และวางแผนครอบครัว', 'Ante  Natal Care & Family  Planning ', 'ANC');
INSERT INTO `tb_department` VALUES ('072', 'กลุ่มงานสุขศึกษาประชาสัมพันธ์', 'Health   Education   Department', 'HED');
INSERT INTO `tb_department` VALUES ('073', 'กลุ่มงานโภชนศาสตร์', 'Nutritional   Department', 'NUT');
INSERT INTO `tb_department` VALUES ('074', 'องค์กรแพทย์', 'Medical Staff Organization', 'MSO');
INSERT INTO `tb_department` VALUES ('075', 'ทีมพัฒนาคุณภาพ', 'Lead Team', 'LED');
INSERT INTO `tb_department` VALUES ('076', 'ทีมประสานงานโรงพยาบาล', 'Facilitator Team', 'FAC');
INSERT INTO `tb_department` VALUES ('077', 'ทีมตรวจเยี่ยมภายใน', 'Internal Surveyor', 'SUV');
INSERT INTO `tb_department` VALUES ('078', 'ศูนย์ประสานงานคุณภาพโรงพยาบาล', 'Quality Improvement  Center', 'QIC');
INSERT INTO `tb_department` VALUES ('079', 'คณะกรรมการสิ่งแวดล้อม', 'Environmental   Committee', 'ENV');
INSERT INTO `tb_department` VALUES ('080', 'คณะกรรมการข้อมูลข่าวสาร/ศูนย์เทคโนโลยีสารสนเทศ', 'Information   Technology  Committee', 'ITC');
INSERT INTO `tb_department` VALUES ('081', 'คณะกรรมการควบคุมและป้องกันการติดเชื้อ', 'Infection Control  Committee', 'IC');
INSERT INTO `tb_department` VALUES ('082', 'คณะกรรมการควบคุมและป้องกันโรคเอดส์', 'AIDS Committee', 'AIDS');
INSERT INTO `tb_department` VALUES ('083', 'ศูนย์อุปกรณ์การแพทย์', 'Medical  Equipment  center', 'MEC');
INSERT INTO `tb_department` VALUES ('084', 'ฝ่ายธุรการ', 'General    Managing   Department\n\n', 'GEM');
INSERT INTO `tb_department` VALUES ('085', 'งานซักฟอก', 'Laundry', 'LAU');
INSERT INTO `tb_department` VALUES ('086', 'กลุ่มการเงิน', 'Accounting  Department', 'ACC');
INSERT INTO `tb_department` VALUES ('087', 'กลุ่มงานพัสดุ', 'Stock', 'STO');
INSERT INTO `tb_department` VALUES ('088', 'กลุ่มงานอาชีวเวชกรรม', 'occupational medicine', 'OM');

-- ----------------------------
-- Table structure for tb_document
-- ----------------------------
DROP TABLE IF EXISTS `tb_document`;
CREATE TABLE `tb_document`  (
  `doc_id` int(11) NOT NULL AUTO_INCREMENT,
  `doc_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `doc_qic_id` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `doc_type` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `doc_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `doc_topic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `doc_dept` varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `doc_eft_date` date NULL DEFAULT NULL,
  `doc_exp_date` date NULL DEFAULT NULL,
  `doc_imp_date` date NULL DEFAULT NULL,
  `doc_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `doc_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  PRIMARY KEY (`doc_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_document
-- ----------------------------
INSERT INTO `tb_document` VALUES (2, NULL, '145', '3', 'NK-WI-OPD-145', '145555555555555', '070', '2025-10-03', NULL, '2025-10-02', '1759379402_NK-WI-OPD-145.pdf', '');
INSERT INTO `tb_document` VALUES (3, NULL, '146', '4', 'NK-SD-COC-146', '1466666666666666666', '069', '2025-10-02', NULL, '2025-10-02', '1759415336_NK-SD-COC-146.pdf', '');
INSERT INTO `tb_document` VALUES (4, NULL, '147', '3', 'NK-WI-OPD-147', 'คู่มือการทำงาน OPD', '070', '2025-10-05', '2026-10-05', '2025-10-05', NULL, 'นำเข้าจาก Excel - 2025-10-05 16:13:25');
INSERT INTO `tb_document` VALUES (6, NULL, '149', '1', 'NK-QM-ADM-149', 'แบบฟอร์ม Admin', '068', '2025-10-07', '2026-10-07', '2025-10-05', '1759657417_NK-QM-ADM-149.pdf', 'นำเข้าจาก Excel - 2025-10-05 16:13:26');
INSERT INTO `tb_document` VALUES (7, NULL, '150', '8', 'NK-OT-OPD-150', 'HAIT 7 Software and Control (Homework)', '070', '2025-10-07', NULL, '2025-10-11', '1759822118_NK-OT-OPD-001.pdf', 'การบ้าน HAIT หมวด 6');
INSERT INTO `tb_document` VALUES (9, NULL, '148', '4', 'NK-SD-COC-148', 'เอกสารมาตรฐาน COC', '069', '2025-10-06', '2026-10-06', '2025-10-10', NULL, 'นำเข้าจาก Excel - 2025-10-10 19:32:35');
INSERT INTO `tb_document` VALUES (10, NULL, '151', '3', 'NK-WI-OPD-151', 'คู่มือการทำงาน 151', '070', '2025-10-05', '2026-10-05', '2025-10-11', NULL, 'นำเข้าจาก Excel - 2025-10-11 16:02:56');
INSERT INTO `tb_document` VALUES (11, NULL, '152', '4', 'NK-SD-COC-152', 'เอกสารมาตรฐาน 152', '069', '2025-10-06', '2026-10-06', '2025-10-11', NULL, 'นำเข้าจาก Excel - 2025-10-11 16:02:56');
INSERT INTO `tb_document` VALUES (12, NULL, '153', '1', 'NK-QM-ADM-153', 'แบบฟอร์ม Admin 153', '068', '2025-10-07', '2026-10-07', '2025-10-11', NULL, 'นำเข้าจาก Excel - 2025-10-11 16:02:56');
INSERT INTO `tb_document` VALUES (13, NULL, '154', '7', 'NK-JN-ITC-009', 'คู่มือการเชื่อมต่อระบบ Provider ID ด้วย OAuth Health ID', '080', '2025-10-11', NULL, '2025-10-11', '1760189940_NK-JN-ITC-009.pdf', '');

-- ----------------------------
-- Table structure for tb_document_edit
-- ----------------------------
DROP TABLE IF EXISTS `tb_document_edit`;
CREATE TABLE `tb_document_edit`  (
  `edit_id` int(11) NOT NULL AUTO_INCREMENT,
  `edit_qic_no` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `edit_round` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `edit_date` date NULL DEFAULT NULL,
  `edit_remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `edit_prepared_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `edit_reviewed_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `edit_approved_by` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `edit_upload_date` date NULL DEFAULT current_timestamp,
  PRIMARY KEY (`edit_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_document_edit
-- ----------------------------
INSERT INTO `tb_document_edit` VALUES (1, '123', '1', '2025-10-01', 'ปรับปรุง เป็นเล่มที่ 2', 'กฤตานนท์ สุลา', 'กัลยารัตน์ เที่ยงภักดี', 'กุลญาดา โคตรวรมมา', '2025-09-30');
INSERT INTO `tb_document_edit` VALUES (2, '145', '1', '2025-10-02', 'ครั้งที่ 2', 'กมลลักษณ์ นิกรเทศ', 'กฤติมา ศักดิ์สุรีย์มงคล', 'กฤติมา ศักดิ์สุรีย์มงคล', '2025-10-02');
INSERT INTO `tb_document_edit` VALUES (4, '150', '1', '2025-10-07', 'ครั้งที่ 1', 'นายประวีร์ ธีราไชยนันท์', 'สิทธิศักดิ์ จามรมาน', 'นายณัฐพล ศรีระษา', '2025-10-07');
INSERT INTO `tb_document_edit` VALUES (5, '151', '1', '2025-10-08', 'ครั้งแรก', 'นายประวีร์ ธีราไชยนันท์', 'สิทธิศักดิ์ จามรมาน', 'นายณัฐพล ศรีระษา', '2025-10-08');
INSERT INTO `tb_document_edit` VALUES (6, '154', '1', '2025-10-11', '', 'นายประวีร์ ธีราไชยนันท์', '', '', '2025-10-11');

-- ----------------------------
-- Table structure for tb_type
-- ----------------------------
DROP TABLE IF EXISTS `tb_type`;
CREATE TABLE `tb_type`  (
  `type_code` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `type_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `type_short_name` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of tb_type
-- ----------------------------
INSERT INTO `tb_type` VALUES ('0', 'Quality Policy (QP)', 'QP');
INSERT INTO `tb_type` VALUES ('1', 'Hospital Policy (HP)', 'HP');
INSERT INTO `tb_type` VALUES ('2', 'Procedure (PR)', 'PR');
INSERT INTO `tb_type` VALUES ('3', 'Workinstruction (WI)', 'WI');
INSERT INTO `tb_type` VALUES ('4', 'Support Document (SD)', 'SD');
INSERT INTO `tb_type` VALUES ('5', 'Plan (PL)', 'PL');
INSERT INTO `tb_type` VALUES ('6', 'Form (FM)', 'FM');
INSERT INTO `tb_type` VALUES ('8', 'Orther (OT)', 'OT');
INSERT INTO `tb_type` VALUES ('7', 'Journal (JN)', 'JN');

SET FOREIGN_KEY_CHECKS = 1;
