-- --------------------------------------------------------

--
-- 表的结构 `CET_Info`
--

CREATE TABLE IF NOT EXISTS `CET_Info` (
  `admissionNum` varchar(15) NOT NULL,
  `examType` varchar(4) NOT NULL,
  `uID` varchar(18) NOT NULL,
  `examDate` int(20) NOT NULL,
  PRIMARY KEY (`admissionNum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `score`
--

CREATE TABLE IF NOT EXISTS `score` (
  `admissionNum` bigint(15) NOT NULL,
  `totalscore` int(11) NOT NULL,
  `listeningscore` int(11) NOT NULL,
  `writingscore` int(11) NOT NULL,
  `readingscore` int(11) NOT NULL,
  `isAbsent` varchar(5) NOT NULL,
  `isCheat` varchar(5) NOT NULL,
  PRIMARY KEY (`admissionNum`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `Student`
--

CREATE TABLE IF NOT EXISTS `Student` (
  `Id_Student` int(11) NOT NULL,
  `StudentName` varchar(255) NOT NULL,
  `Sex` int(4) NOT NULL,
  `ClassName` varchar(255) NOT NULL,
  `StudentDept` varchar(255) NOT NULL,
  `uID` varchar(18) NOT NULL,
  PRIMARY KEY (`Id_Student`,`uID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
