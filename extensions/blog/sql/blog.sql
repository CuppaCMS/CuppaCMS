DROP TABLE IF EXISTS `ex_blog_articles`;
CREATE TABLE `ex_blog_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) DEFAULT NULL,
  `alias` varchar(250) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `categories` varchar(255) DEFAULT NULL,
  `language` varchar(10) NOT NULL DEFAULT '',
  `date` date DEFAULT NULL,
  `enabled` int(11) NOT NULL DEFAULT '1',
  `user` int(11) DEFAULT NULL,
  `abstract` text,
  `content` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`alias`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

INSERT INTO `ex_blog_articles` VALUES (10,'Justin Bieber breaks major Spotify record with','justin-bieber-breaks-major-spotify-record-with-what-do-you-mean','media/blog/img1_1451953097.jpg','Facebook, Instagram, Twitter, Software,','[\"3\"]','','2023-12-31',1,1,'Non est ista, inquam, Piso, magna dissensio. Duo Reges: constructio interrete. Nunc de hominis summo bono quaeritur; Addidisti ad extremum etiam indoctum fuisse. Naturales divitias dixit parabiles esse, quod parvo esset natura contenta.','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Eorum enim est haec querela, qui sibi cari sunt seseque diligunt. Nam, ut sint illa vendibiliora, haec uberiora certe sunt. Ab hoc autem quaedam non melius quam veteres, quaedam omnino relicta. Animum autem reliquis rebus ita perfecit, ut corpus; Aliter homines, aliter philosophos loqui putas oportere? Bonum integritas corporis: misera debilitas. Respondeat totidem verbis. Qui autem de summo bono dissentit de tota philosophiae ratione dissentit.</p>\n<ul>\n<li>Cuius similitudine perspecta in formarum specie ac dignitate transitum est ad honestatem dictorum atque factorum.</li>\n<li>At cum tuis cum disseras, multa sunt audienda etiam de obscenis voluptatibus, de quibus ab Epicuro saepissime dicitur.</li>\n<li>Sic enim censent, oportunitatis esse beate vivere.</li>\n<li>Dolor ergo, id est summum malum, metuetur semper, etiamsi non aderit;</li>\n<li>Magni enim aestimabat pecuniam non modo non contra leges, sed etiam legibus partam.</li>\n<li>Moriatur, inquit.</li>\n</ul>\n<p>Quod autem principium officii quaerunt, melius quam Pyrrho; Expectoque quid ad id, quod quaerebam, respondeas.</p>\n<p>Ac ne plura complectar-sunt enim innumerabilia-, bene laudata virtus voluptatis aditus intercludat necesse est. Minime vero, inquit ille, consentit. Non enim, si omnia non sequebatur, idcirco non erat ortus illinc. Quamquam tu hanc copiosiorem etiam soles dicere. Traditur, inquit, ab Epicuro ratio neglegendi doloris. Egone non intellego, quid sit don Graece, Latine voluptas? Quam si explicavisset, non tam haesitaret. At eum nihili facit;</p>\n<ol>\n<li>Mene ergo et Triarium dignos existimas, apud quos turpiter loquare?</li>\n<li>Non quam nostram quidem, inquit Pomponius iocans;</li>\n<li>Non potes, nisi retexueris illa.</li>\n<li>Immo sit sane nihil melius, inquam-nondum enim id quaero-, num propterea idem voluptas est, quod, ut ita dicam, indolentia?</li>\n</ol>\n<p>Duo Reges: constructio interrete. Qui autem de summo bono dissentit de tota philosophiae ratione dissentit. Quae si potest singula consolando levare, universa quo modo sustinebit? Sic enim censent, oportunitatis esse beate vivere. Iam id ipsum absurdum, maximum malum neglegi. Sed hoc sane concedamus. Sed ego in hoc resisto; Tum ille: Ain tandem? Quantum Aristoxeni ingenium consumptum videmus in musicis?</p>\n<p>Inde sermone vario sex illa a Dipylo stadia confecimus. Nam quibus rebus efficiuntur voluptates, eae non sunt in potestate sapientis. Dempta enim aeternitate nihilo beatior Iuppiter quam Epicurus; Bona autem corporis huic sunt, quod posterius posui, similiora. Scientiam pollicentur, quam non erat mirum sapientiae cupido patria esse cariorem. Equidem e Cn.</p>\n<p>Aliter enim explicari, quod quaeritur, non potest. Sapientem locupletat ipsa natura, cuius divitias Epicurus parabiles esse docuit. Istam voluptatem perpetuam quis potest praestare sapienti? Sapiens autem semper beatus est et est aliquando in dolore; Quis non odit sordidos, vanos, leves, futtiles? Quae quidem vel cum periculo est quaerenda vobis; At eum nihili facit;</p>\n<ol>\n<li>Mihi vero, inquit, placet agi subtilius et, ut ipse dixisti, pressius.</li>\n<li>Maximas vero virtutes iacere omnis necesse est voluptate dominante.</li>\n<li>Equidem etiam Epicurum, in physicis quidem, Democriteum puto.</li>\n<li>An est aliquid per se ipsum flagitiosum, etiamsi nulla comitetur infamia?</li>\n</ol>\n<p>Istam voluptatem perpetuam quis potest praestare sapienti? Dic in quovis conventu te omnia facere, ne doleas. Polycratem Samium felicem appellabant. At iam decimum annum in spelunca iacet.</p>\n<ul>\n<li>Cuius quidem, quoniam Stoicus fuit, sententia condemnata mihi videtur esse inanitas ista verborum.</li>\n<li>Quae enim adhuc protulisti, popularia sunt, ego autem a te elegantiora desidero.</li>\n</ul>\n<p>Nunc vides, quid faciat. Sed quanta sit alias, nunc tantum possitne esse tanta. Sed tamen est aliquid, quod nobis non liceat, liceat illis. Conferam tecum, quam cuique verso rem subicias; Quamquam haec quidem praeposita recte et reiecta dicere licebit. Progredientibus autem aetatibus sensim tardeve potius quasi nosmet ipsos cognoscimus.</p>\n<p>Honesta oratio, Socratica, Platonis etiam. Quorum altera prosunt, nocent altera. Aeque enim contingit omnibus fidibus, ut incontentae sint. Naturales divitias dixit parabiles esse, quod parvo esset natura contenta. Sullae consulatum? Paulum, cum regem Persem captum adduceret, eodem flumine invectio? Cur post Tarentum ad Archytam? Bonum incolumis acies: misera caecitas.</p>\n<p>Quae similitudo in genere etiam humano apparet. Si enim ita est, vide ne facinus facias, cum mori suadeas. Unum nescio, quo modo possit, si luxuriosus sit, finitas cupiditates habere. Prodest, inquit, mihi eo esse animo. Eiuro, inquit adridens, iniquum, hac quidem de re; Ita relinquet duas, de quibus etiam atque etiam consideret.</p>');
INSERT INTO `ex_blog_articles` VALUES (11,'New Google Street View app lets you add your own 360-degree photos','new-google-street-view-app-lets-you-add-your-own-360-degree-photos','media/blog/img2_1451953089.jpg','Communication, Business, Internet,','[\"2\",\"3\"]','','2020-10-29',1,1,'Non est ista, inquam, Piso, magna dissensio. Duo Reges: constructio interrete. Nunc de hominis summo bono quaeritur; Addidisti ad extremum etiam indoctum fuisse. Naturales divitias dixit parabiles esse, quod parvo esset natura contenta.','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Eorum enim est haec querela, qui sibi cari sunt seseque diligunt. Nam, ut sint illa vendibiliora, haec uberiora certe sunt. Ab hoc autem quaedam non melius quam veteres, quaedam omnino relicta. Animum autem reliquis rebus ita perfecit, ut corpus; Aliter homines, aliter philosophos loqui putas oportere? Bonum integritas corporis: misera debilitas. Respondeat totidem verbis. Qui autem de summo bono dissentit de tota philosophiae ratione dissentit.</p>\n<ul>\n<li>Cuius similitudine perspecta in formarum specie ac dignitate transitum est ad honestatem dictorum atque factorum.</li>\n<li>At cum tuis cum disseras, multa sunt audienda etiam de obscenis voluptatibus, de quibus ab Epicuro saepissime dicitur.</li>\n<li>Sic enim censent, oportunitatis esse beate vivere.</li>\n<li>Dolor ergo, id est summum malum, metuetur semper, etiamsi non aderit;</li>\n<li>Magni enim aestimabat pecuniam non modo non contra leges, sed etiam legibus partam.</li>\n<li>Moriatur, inquit.</li>\n</ul>\n<p>Quod autem principium officii quaerunt, melius quam Pyrrho; Expectoque quid ad id, quod quaerebam, respondeas.</p>\n<p>Ac ne plura complectar-sunt enim innumerabilia-, bene laudata virtus voluptatis aditus intercludat necesse est. Minime vero, inquit ille, consentit. Non enim, si omnia non sequebatur, idcirco non erat ortus illinc. Quamquam tu hanc copiosiorem etiam soles dicere. Traditur, inquit, ab Epicuro ratio neglegendi doloris. Egone non intellego, quid sit don Graece, Latine voluptas? Quam si explicavisset, non tam haesitaret. At eum nihili facit;</p>\n<ol>\n<li>Mene ergo et Triarium dignos existimas, apud quos turpiter loquare?</li>\n<li>Non quam nostram quidem, inquit Pomponius iocans;</li>\n<li>Non potes, nisi retexueris illa.</li>\n<li>Immo sit sane nihil melius, inquam-nondum enim id quaero-, num propterea idem voluptas est, quod, ut ita dicam, indolentia?</li>\n</ol>\n<p>Duo Reges: constructio interrete. Qui autem de summo bono dissentit de tota philosophiae ratione dissentit. Quae si potest singula consolando levare, universa quo modo sustinebit? Sic enim censent, oportunitatis esse beate vivere. Iam id ipsum absurdum, maximum malum neglegi. Sed hoc sane concedamus. Sed ego in hoc resisto; Tum ille: Ain tandem? Quantum Aristoxeni ingenium consumptum videmus in musicis?</p>\n<p>Inde sermone vario sex illa a Dipylo stadia confecimus. Nam quibus rebus efficiuntur voluptates, eae non sunt in potestate sapientis. Dempta enim aeternitate nihilo beatior Iuppiter quam Epicurus; Bona autem corporis huic sunt, quod posterius posui, similiora. Scientiam pollicentur, quam non erat mirum sapientiae cupido patria esse cariorem. Equidem e Cn.</p>\n<p>Aliter enim explicari, quod quaeritur, non potest. Sapientem locupletat ipsa natura, cuius divitias Epicurus parabiles esse docuit. Istam voluptatem perpetuam quis potest praestare sapienti? Sapiens autem semper beatus est et est aliquando in dolore; Quis non odit sordidos, vanos, leves, futtiles? Quae quidem vel cum periculo est quaerenda vobis; At eum nihili facit;</p>\n<ol>\n<li>Mihi vero, inquit, placet agi subtilius et, ut ipse dixisti, pressius.</li>\n<li>Maximas vero virtutes iacere omnis necesse est voluptate dominante.</li>\n<li>Equidem etiam Epicurum, in physicis quidem, Democriteum puto.</li>\n<li>An est aliquid per se ipsum flagitiosum, etiamsi nulla comitetur infamia?</li>\n</ol>\n<p>Istam voluptatem perpetuam quis potest praestare sapienti? Dic in quovis conventu te omnia facere, ne doleas. Polycratem Samium felicem appellabant. At iam decimum annum in spelunca iacet.</p>\n<ul>\n<li>Cuius quidem, quoniam Stoicus fuit, sententia condemnata mihi videtur esse inanitas ista verborum.</li>\n<li>Quae enim adhuc protulisti, popularia sunt, ego autem a te elegantiora desidero.</li>\n</ul>\n<p>Nunc vides, quid faciat. Sed quanta sit alias, nunc tantum possitne esse tanta. Sed tamen est aliquid, quod nobis non liceat, liceat illis. Conferam tecum, quam cuique verso rem subicias; Quamquam haec quidem praeposita recte et reiecta dicere licebit. Progredientibus autem aetatibus sensim tardeve potius quasi nosmet ipsos cognoscimus.</p>\n<p>Honesta oratio, Socratica, Platonis etiam. Quorum altera prosunt, nocent altera. Aeque enim contingit omnibus fidibus, ut incontentae sint. Naturales divitias dixit parabiles esse, quod parvo esset natura contenta. Sullae consulatum? Paulum, cum regem Persem captum adduceret, eodem flumine invectio? Cur post Tarentum ad Archytam? Bonum incolumis acies: misera caecitas.</p>\n<p>Quae similitudo in genere etiam humano apparet. Si enim ita est, vide ne facinus facias, cum mori suadeas. Unum nescio, quo modo possit, si luxuriosus sit, finitas cupiditates habere. Prodest, inquit, mihi eo esse animo. Eiuro, inquit adridens, iniquum, hac quidem de re; Ita relinquet duas, de quibus etiam atque etiam consideret.</p>');
INSERT INTO `ex_blog_articles` VALUES (12,'President ObamTwo new','president-obamtwo-new-empire-songs-will-tide-you-over-until-cookie-returns-to-tv','media/blog/img1_1451953082.jpg','Design, CMS, Advertising, Marketing, Internet,','[\"4\",\"2\"]','','2017-01-01',1,1,'Non est ista, inquam, Piso, magna dissensio. Duo Reges: constructio interrete. Nunc de hominis summo bono quaeritur; Addidisti ad extremum etiam indoctum fuisse. Naturales divitias dixit parabiles esse, quod parvo esset natura contenta.','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Eorum enim est haec querela, qui sibi cari sunt seseque diligunt. Nam, ut sint illa vendibiliora, haec uberiora certe sunt. Ab hoc autem quaedam non melius quam veteres, quaedam omnino relicta. Animum autem reliquis rebus ita perfecit, ut corpus; Aliter homines, aliter philosophos loqui putas oportere? Bonum integritas corporis: misera debilitas. Respondeat totidem verbis. Qui autem de summo bono dissentit de tota philosophiae ratione dissentit.</p>\n<ul>\n<li>Cuius similitudine perspecta in formarum specie ac dignitate transitum est ad honestatem dictorum atque factorum.</li>\n<li>At cum tuis cum disseras, multa sunt audienda etiam de obscenis voluptatibus, de quibus ab Epicuro saepissime dicitur.</li>\n<li>Sic enim censent, oportunitatis esse beate vivere.</li>\n<li>Dolor ergo, id est summum malum, metuetur semper, etiamsi non aderit;</li>\n<li>Magni enim aestimabat pecuniam non modo non contra leges, sed etiam legibus partam.</li>\n<li>Moriatur, inquit.</li>\n</ul>\n<p>Quod autem principium officii quaerunt, melius quam Pyrrho; Expectoque quid ad id, quod quaerebam, respondeas.</p>\n<p>Ac ne plura complectar-sunt enim innumerabilia-, bene laudata virtus voluptatis aditus intercludat necesse est. Minime vero, inquit ille, consentit. Non enim, si omnia non sequebatur, idcirco non erat ortus illinc. Quamquam tu hanc copiosiorem etiam soles dicere. Traditur, inquit, ab Epicuro ratio neglegendi doloris. Egone non intellego, quid sit don Graece, Latine voluptas? Quam si explicavisset, non tam haesitaret. At eum nihili facit;</p>\n<ol>\n<li>Mene ergo et Triarium dignos existimas, apud quos turpiter loquare?</li>\n<li>Non quam nostram quidem, inquit Pomponius iocans;</li>\n<li>Non potes, nisi retexueris illa.</li>\n<li>Immo sit sane nihil melius, inquam-nondum enim id quaero-, num propterea idem voluptas est, quod, ut ita dicam, indolentia?</li>\n</ol>\n<p>Duo Reges: constructio interrete. Qui autem de summo bono dissentit de tota philosophiae ratione dissentit. Quae si potest singula consolando levare, universa quo modo sustinebit? Sic enim censent, oportunitatis esse beate vivere. Iam id ipsum absurdum, maximum malum neglegi. Sed hoc sane concedamus. Sed ego in hoc resisto; Tum ille: Ain tandem? Quantum Aristoxeni ingenium consumptum videmus in musicis?</p>\n<p>Inde sermone vario sex illa a Dipylo stadia confecimus. Nam quibus rebus efficiuntur voluptates, eae non sunt in potestate sapientis. Dempta enim aeternitate nihilo beatior Iuppiter quam Epicurus; Bona autem corporis huic sunt, quod posterius posui, similiora. Scientiam pollicentur, quam non erat mirum sapientiae cupido patria esse cariorem. Equidem e Cn.</p>\n<p>Aliter enim explicari, quod quaeritur, non potest. Sapientem locupletat ipsa natura, cuius divitias Epicurus parabiles esse docuit. Istam voluptatem perpetuam quis potest praestare sapienti? Sapiens autem semper beatus est et est aliquando in dolore; Quis non odit sordidos, vanos, leves, futtiles? Quae quidem vel cum periculo est quaerenda vobis; At eum nihili facit;</p>\n<ol>\n<li>Mihi vero, inquit, placet agi subtilius et, ut ipse dixisti, pressius.</li>\n<li>Maximas vero virtutes iacere omnis necesse est voluptate dominante.</li>\n<li>Equidem etiam Epicurum, in physicis quidem, Democriteum puto.</li>\n<li>An est aliquid per se ipsum flagitiosum, etiamsi nulla comitetur infamia?</li>\n</ol>\n<p>Istam voluptatem perpetuam quis potest praestare sapienti? Dic in quovis conventu te omnia facere, ne doleas. Polycratem Samium felicem appellabant. At iam decimum annum in spelunca iacet.</p>\n<ul>\n<li>Cuius quidem, quoniam Stoicus fuit, sententia condemnata mihi videtur esse inanitas ista verborum.</li>\n<li>Quae enim adhuc protulisti, popularia sunt, ego autem a te elegantiora desidero.</li>\n</ul>\n<p>Nunc vides, quid faciat. Sed quanta sit alias, nunc tantum possitne esse tanta. Sed tamen est aliquid, quod nobis non liceat, liceat illis. Conferam tecum, quam cuique verso rem subicias; Quamquam haec quidem praeposita recte et reiecta dicere licebit. Progredientibus autem aetatibus sensim tardeve potius quasi nosmet ipsos cognoscimus.</p>\n<p>Honesta oratio, Socratica, Platonis etiam. Quorum altera prosunt, nocent altera. Aeque enim contingit omnibus fidibus, ut incontentae sint. Naturales divitias dixit parabiles esse, quod parvo esset natura contenta. Sullae consulatum? Paulum, cum regem Persem captum adduceret, eodem flumine invectio? Cur post Tarentum ad Archytam? Bonum incolumis acies: misera caecitas.</p>\n<p>Quae similitudo in genere etiam humano apparet. Si enim ita est, vide ne facinus facias, cum mori suadeas. Unum nescio, quo modo possit, si luxuriosus sit, finitas cupiditates habere. Prodest, inquit, mihi eo esse animo. Eiuro, inquit adridens, iniquum, hac quidem de re; Ita relinquet duas, de quibus etiam atque etiam consideret.</p>');
DROP TABLE IF EXISTS `ex_blog_categories`;
CREATE TABLE `ex_blog_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

INSERT INTO `ex_blog_categories` VALUES (1,'Notas de Prensa','notas-de-prensa',0);
INSERT INTO `ex_blog_categories` VALUES (2,'Revista Pacifico Life','revista-pacifico-life',0);
INSERT INTO `ex_blog_categories` VALUES (3,'Eventos','eventos',0);
INSERT INTO `ex_blog_categories` VALUES (4,'Proyectos','proyectos',0);
INSERT INTO `ex_blog_categories` VALUES (10,'t\"ufik\'s the bes','tufiks-the-bes',0);
DROP TABLE IF EXISTS `ex_tags`;
CREATE TABLE `ex_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;

INSERT INTO `ex_tags` VALUES (1,'Design');
INSERT INTO `ex_tags` VALUES (2,'Art');
INSERT INTO `ex_tags` VALUES (3,'Entertainment');
INSERT INTO `ex_tags` VALUES (4,'Education');
INSERT INTO `ex_tags` VALUES (5,'Advertising');
INSERT INTO `ex_tags` VALUES (6,'Environment');
INSERT INTO `ex_tags` VALUES (7,'Marketing');
INSERT INTO `ex_tags` VALUES (8,'Photography');
INSERT INTO `ex_tags` VALUES (9,'Social Media');
INSERT INTO `ex_tags` VALUES (10,'Travel');
INSERT INTO `ex_tags` VALUES (11,'Technology');
INSERT INTO `ex_tags` VALUES (13,'Science');
INSERT INTO `ex_tags` VALUES (14,'Internet');
INSERT INTO `ex_tags` VALUES (15,'Culture');
INSERT INTO `ex_tags` VALUES (16,'Sports');
INSERT INTO `ex_tags` VALUES (17,'Reviews');
INSERT INTO `ex_tags` VALUES (18,'Software');
INSERT INTO `ex_tags` VALUES (19,'Tips');
INSERT INTO `ex_tags` VALUES (20,'Web');
INSERT INTO `ex_tags` VALUES (21,'Video');
INSERT INTO `ex_tags` VALUES (22,'Blog');
INSERT INTO `ex_tags` VALUES (23,'Business');
INSERT INTO `ex_tags` VALUES (25,'Development');
INSERT INTO `ex_tags` VALUES (26,'Computers');
INSERT INTO `ex_tags` VALUES (27,'Communication');
INSERT INTO `ex_tags` VALUES (28,'Responsive');
INSERT INTO `ex_tags` VALUES (30,'Economy');
INSERT INTO `ex_tags` VALUES (31,'Events');
INSERT INTO `ex_tags` VALUES (32,'Games');
INSERT INTO `ex_tags` VALUES (33,'Google');
INSERT INTO `ex_tags` VALUES (34,'Facebook');
INSERT INTO `ex_tags` VALUES (35,'Graphic Design');
INSERT INTO `ex_tags` VALUES (36,'Government');
INSERT INTO `ex_tags` VALUES (37,'Home');
INSERT INTO `ex_tags` VALUES (38,'Investing');
INSERT INTO `ex_tags` VALUES (39,'Investment');
INSERT INTO `ex_tags` VALUES (40,'Music');
INSERT INTO `ex_tags` VALUES (41,'Opinion');
INSERT INTO `ex_tags` VALUES (42,'Organization');
INSERT INTO `ex_tags` VALUES (45,'Relationships');
INSERT INTO `ex_tags` VALUES (47,'Style');
INSERT INTO `ex_tags` VALUES (48,'Strategy');
INSERT INTO `ex_tags` VALUES (51,'Transport');
INSERT INTO `ex_tags` VALUES (52,'Twitter');
INSERT INTO `ex_tags` VALUES (54,'Pinterest');
INSERT INTO `ex_tags` VALUES (55,'LinkedIn');
INSERT INTO `ex_tags` VALUES (56,'Instagram');
INSERT INTO `ex_tags` VALUES (57,'University');
INSERT INTO `ex_tags` VALUES (58,'Vacations');
INSERT INTO `ex_tags` VALUES (59,'Videos');
INSERT INTO `ex_tags` VALUES (61,'Web 2.0');
INSERT INTO `ex_tags` VALUES (62,'Women');
INSERT INTO `ex_tags` VALUES (63,'Men');
INSERT INTO `ex_tags` VALUES (64,'Youtube');
INSERT INTO `ex_tags` VALUES (65,'Download');
INSERT INTO `ex_tags` VALUES (66,'Domain');
INSERT INTO `ex_tags` VALUES (67,'Digital');
INSERT INTO `ex_tags` VALUES (68,'CMS');
INSERT INTO `ex_tags` VALUES (69,'CEO');
INSERT INTO `ex_tags` VALUES (70,'SEM');
