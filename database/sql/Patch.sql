--일정관리에서 시간마당을 추가함
ALTER TABLE `tb_person_schedule`
ADD COLUMN `startTime`  time NULL AFTER `endDate`,
ADD COLUMN `endTime`  time NULL AFTER `startTime`;

--2017년 12월 11일
ALTER TABLE `tb_equipment_particular`
MODIFY COLUMN `id`  int(11) NOT NULL AUTO_INCREMENT FIRST,
AUTO_INCREMENT = 2000 ;

ALTER TABLE `tb_equipment_parts`
MODIFY COLUMN `id`  int(11) NOT NULL AUTO_INCREMENT FIRST ;
--2017 12 -13
ALTER TABLE `tb_person_schedule`
ADD COLUMN `attend_user`  varchar(50) NOT NULL AFTER `endTime`;
ALTER TABLE `tb_person_schedule`
CHANGE COLUMN `Id` `id`  int(11) NOT NULL AUTO_INCREMENT FIRST ;
--2017 12-13
ALTER TABLE `tb_ship`
CHANGE COLUMN `created_at` `create_at`  datetime NULL DEFAULT NULL AFTER `person_num`;
--2017 12-17
ALTER TABLE `tbl_others`
MODIFY COLUMN `OthersId`  int(11) NOT NULL AUTO_INCREMENT FIRST ,
AUTO_INCREMENT=520;