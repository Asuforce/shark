#user(passwordはasdf1234)
insert into user(user_name, password, name, mail_address,created) values('ichiki','eac3d27fbe914cada7e3c585e9a2472f161f4641471626fad64200a63a286c9d','佐藤','ichiki@project.jp',now());
insert into user(user_name, password, name, mail_address,created) values('hiroaki','1df7ee427e8b5078c5d956dad014fe41d4f37b8778e38a8ae456f94d9df8bd8c','新福','hiroaki@project.jp',now());
insert into user(user_name, password, name, mail_address,created) values('haruna','8d70d0cd6318bc54660c3193047224c204d0145b9b9d4e0372836d5ea694a058','池田','haruna@project.jp',now());
insert into user(user_name, password, name, mail_address,created) values('shun','11f38e5b57ed9c8369cdd95a170306ea78d3f11fd08187f0c7bb2513a8cc8ecc','西辻','sun@project.jp',now());
insert into user(user_name, password, name, mail_address,created) values('hayato','edf5667b9b6cc1eea6af02ca8a23c6b768f5f00241b994677f582f182ed51e4f','村木','hayato@project.jp',now());
insert into user(user_name, password, name, mail_address,created) values('takahiro','4d767e78ecd079e3f538424694ab4a80aed203e6fe4490d313d181a8d00106db','山崎','takahiro@project.jp',now());
insert into user(user_name, password, name, mail_address,created) values('keisuke','a754c2ea823cc7fe9111d12821338f699b1a6bf4415c4676744b0cdac0fec089','遠藤','keisuke@project.jp',now());
insert into user(user_name, password, name, mail_address,created) values('yoko','cae756432573a8c2ad5907222e318c36815d8e7a1e64f143b82b192ddf0ab796','出井','yoko@project.jp',now());

#profile ファイルパスは各自で指定してください
insert into profile(user_id, sex, introduction,pro_image) values(1, 0, 'いちきです。', '/profile_image/ichiki.jpg');
insert into profile(user_id, sex, introduction,pro_image) values(2, 0, 'ひろあきです。', '/profile_image/hiroaki.jpg');
insert into profile(user_id, sex, introduction,pro_image) values(3, 1, 'はるなです。', '/profile_image/haruna.jpg');
insert into profile(user_id, sex, introduction,pro_image) values(4, 0, 'しゅんです。', '/profile_image/shun.jpg');
insert into profile(user_id, sex, introduction,pro_image) values(5, 0, 'はやとです。', '/profile_image/hayato.jpg');
insert into profile(user_id, sex, introduction,pro_image) values(6, 0, 'たかひろです。', '/profile_image/takahiro.jpg');
insert into profile(user_id, sex, introduction,pro_image) values(7, 0, 'けいすけです。', '/profile_image/keisuke.jpg');
insert into profile(user_id, sex, introduction,pro_image) values(8, 1, 'ようこです。', '/profile_image/yoko.jpg');

#conversation
insert into conversation(conversation_id, user_id, count) values(1, 1, 0);
insert into conversation(conversation_id, user_id, count) values(1, 2, 0);
insert into conversation(conversation_id, user_id, count) values(2, 1, 0);
insert into conversation(conversation_id, user_id, count) values(2, 3, 0);
insert into conversation(conversation_id, user_id, count) values(3, 1, 0);
insert into conversation(conversation_id, user_id, count) values(3, 4, 0);
insert into conversation(conversation_id, user_id, count) values(4, 1, 0);
insert into conversation(conversation_id, user_id, count) values(4, 5, 0);

#message
insert into message(conversation_id, user_id, body,is_read,created) values(1, 1, 'メッセージ1',0,now());
insert into message(conversation_id, user_id, body,is_read,created) values(1, 2, 'メッセージ2',0,now());
insert into message(conversation_id, user_id, body,is_read,created) values(1, 1, 'メッセージ3',0,now());
insert into message(conversation_id, user_id, body,is_read,created) values(1, 2, 'メッセージ4',0,now());
insert into message(conversation_id, user_id, body,is_read,created) values(1, 1, 'メッセージ5',0,now());
insert into message(conversation_id, user_id, body,is_read,created) values(1, 2, 'メッセージ6',0,now());
insert into message(conversation_id, user_id, body,is_read,created) values(1, 1, 'メッセージ7',0,now());
insert into message(conversation_id, user_id, body,is_read,created) values(1, 2, 'メッセージ8',0,now());
insert into message(conversation_id, user_id, body,is_read,created) values(1, 1, 'メッセージ9',0,now());
insert into message(conversation_id, user_id, body,is_read,created) values(1, 2, 'メッセージ10',0,now());
insert into message(conversation_id, user_id, body,is_read,created) values(1, 1, 'メッセージ11',0,now());
insert into message(conversation_id, user_id, body,is_read,created) values(1, 2, 'メッセージ12',0,now());
insert into message(conversation_id, user_id, body,is_read,created) values(1, 1, 'メッセージ13',0,now());
insert into message(conversation_id, user_id, body,is_read,created) values(1, 2, 'メッセージ14',0,now());
insert into message(conversation_id, user_id, body,is_read,created) values(1, 1, 'メッセージ15',0,now());

#material
insert into material(user_id, instrument_id, submit_date, material_comment) values(1, 1, now(),'素材テスト1');
insert into material(user_id, instrument_id, submit_date, material_comment) values(2, 2, now(),'素材テスト2');
insert into material(user_id, instrument_id, submit_date, material_comment) values(3, 3, now(),'素材テスト3');
insert into material(user_id, instrument_id, submit_date, material_comment) values(4, 4, now(),'素材テスト4');
insert into material(user_id, instrument_id, submit_date, material_comment) values(5, 5, now(),'素材テスト5');
insert into material(user_id, instrument_id, submit_date, material_comment) values(6, 1, now(),'素材テスト6');
insert into material(user_id, instrument_id, submit_date, material_comment) values(7, 2, now(),'素材テスト7');
insert into material(user_id, instrument_id, submit_date, material_comment) values(8, 3, now(),'素材テスト8');
insert into material(user_id, instrument_id, submit_date, material_comment) values(1, 4, now(),'素材テスト9');
insert into material(user_id, instrument_id, submit_date, material_comment) values(2, 5, now(),'素材テスト10');
insert into material(user_id, instrument_id, submit_date, material_comment) values(3, 1, now(),'素材テスト11');
insert into material(user_id, instrument_id, submit_date, material_comment) values(4, 2, now(),'素材テスト12');
insert into material(user_id, instrument_id, submit_date, material_comment) values(5, 3, now(),'素材テスト13');
insert into material(user_id, instrument_id, submit_date, material_comment) values(6, 4, now(),'素材テスト14');
insert into material(user_id, instrument_id, submit_date, material_comment) values(7, 5, now(),'素材テスト15');

#record
insert into record(record_id,material_id) values(1,1);
insert into record(record_id,material_id) values(1,2);
insert into record(record_id,material_id) values(1,3);
insert into record(record_id,material_id) values(1,4);
insert into record(record_id,material_id) values(1,5);
insert into record(record_id,material_id) values(1,6);
insert into record(record_id,material_id) values(2,1);
insert into record(record_id,material_id) values(2,3);
insert into record(record_id,material_id) values(2,6);
insert into record(record_id,material_id) values(2,8);
insert into record(record_id,material_id) values(2,10);
insert into record(record_id,material_id) values(2,12);
insert into record(record_id,material_id) values(3,1);
insert into record(record_id,material_id) values(3,2);
insert into record(record_id,material_id) values(3,4);
insert into record(record_id,material_id) values(3,8);
insert into record(record_id,material_id) values(3,12);
insert into record(record_id,material_id) values(3,14);

#record_data
insert into record_data(user_id, record_name, record_date, record_comment) values(1, 'レコード1', now(),'レコードテスト1');
insert into record_data(user_id, record_name, record_date, record_comment) values(2, 'レコード2', now(),'レコードテスト2');
insert into record_data(user_id, record_name, record_date, record_comment) values(3, 'レコード3', now(),'レコードテスト3');

#instrument(1=ボーカル 2=ギター　3=ベース 4=ドラム 5=その他)
#ファイルパスは各自で指定してください("satouikki"を自分のユーザ名でできるはず)
insert into instrument(instrument_id, instrument_name, instrument_image) values(1, 'ボーカル', '/instrument_icon/vocal.png');
insert into instrument(instrument_id, instrument_name, instrument_image) values(2, 'ギター', '/instrument_icon/guitar.png');
insert into instrument(instrument_id, instrument_name, instrument_image) values(3, 'ベース', '/instrument_icon/bass.png');
insert into instrument(instrument_id, instrument_name, instrument_image) values(4, 'ドラム', '/instrument_icon/drum.png');
insert into instrument(instrument_id, instrument_name, instrument_image) values(5, 'その他', '/instrument_icon/other.png');

#fav_material
insert into fav_material(user_id, material_id, fav_mate_date) values(1, 2,now());
insert into fav_material(user_id, material_id, fav_mate_date) values(1, 3,now());
insert into fav_material(user_id, material_id, fav_mate_date) values(2, 4,now());
insert into fav_material(user_id, material_id, fav_mate_date) values(2, 5,now());
insert into fav_material(user_id, material_id, fav_mate_date) values(3, 6,now());
insert into fav_material(user_id, material_id, fav_mate_date) values(3, 7,now());

#fav_record
insert into fav_record(user_id, record_id, fav_reco_date) values(1, 2,now());
insert into fav_record(user_id, record_id, fav_reco_date) values(1, 3,now());
insert into fav_record(user_id, record_id, fav_reco_date) values(2, 1,now());
insert into fav_record(user_id, record_id, fav_reco_date) values(2, 3,now());
insert into fav_record(user_id, record_id, fav_reco_date) values(3, 1,now());
insert into fav_record(user_id, record_id, fav_reco_date) values(3, 2,now());

#follow
insert into follow(user_id, follow_id, follow_date) values(1, 2,now());
insert into follow(user_id, follow_id, follow_date) values(1, 3,now());
insert into follow(user_id, follow_id, follow_date) values(1, 4,now());
insert into follow(user_id, follow_id, follow_date) values(1, 5,now());
insert into follow(user_id, follow_id, follow_date) values(1, 6,now());
insert into follow(user_id, follow_id, follow_date) values(1, 7,now());
insert into follow(user_id, follow_id, follow_date) values(1, 8,now());
insert into follow(user_id, follow_id, follow_date) values(2, 1,now());
insert into follow(user_id, follow_id, follow_date) values(2, 3,now());
insert into follow(user_id, follow_id, follow_date) values(2, 4,now());
insert into follow(user_id, follow_id, follow_date) values(2, 5,now());
insert into follow(user_id, follow_id, follow_date) values(3, 6,now());
insert into follow(user_id, follow_id, follow_date) values(3, 7,now());
insert into follow(user_id, follow_id, follow_date) values(3, 8,now());
