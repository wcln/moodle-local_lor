-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 24, 2018 at 10:58 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.0.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `moodle2`
--

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lor_category`
--

CREATE TABLE `mdl_lor_category` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mdl_lor_category`
--

INSERT INTO `mdl_lor_category` (`id`, `name`) VALUES
(2, 'Math'),
(3, 'Earth Science'),
(4, 'Chemistry'),
(5, 'Physics'),
(6, 'English'),
(7, 'Biology'),
(8, 'Other'),
(9, 'Socials');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lor_content`
--

CREATE TABLE `mdl_lor_content` (
  `id` bigint(20) NOT NULL,
  `type` bigint(20) NOT NULL,
  `title` varchar(250) NOT NULL,
  `image` varchar(250) NOT NULL,
  `link` varchar(250) NOT NULL,
  `date_created` date NOT NULL,
  `status` varchar(15) DEFAULT 'approved',
  `author_email` varchar(250) DEFAULT NULL,
  `platform` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mdl_lor_content`
--

INSERT INTO `mdl_lor_content` (`id`, `type`, `title`, `image`, `link`, `date_created`, `status`, `author_email`, `platform`) VALUES
(312, 1, 'Multiplication Balloons', 'https://bclearningnetwork.com/LOR/games/balloons/preview.png', 'https://bclearningnetwork.com/LOR/games/balloons/multiplication.html', '2017-06-20', 'approved', NULL, 1),
(313, 1, 'Greatest Common Factor', 'https://bclearningnetwork.com/LOR/games/balloons/preview.png', 'https://bclearningnetwork.com/LOR/games/balloons/balloons.php?title=Greatest Common Factor', '2017-06-01', 'approved', NULL, 1),
(314, 1, 'Free Body Diagrams #1', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/fbd1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=fbd1', '2017-05-01', 'approved', NULL, 1),
(315, 1, 'Food Chains', 'https://bclearningnetwork.com/LOR/games/arrange/versions/foodchains/preview.png', 'https://bclearningnetwork.com/LOR/games/arrange/arrange.php?title=foodchains', '2017-04-01', 'approved', NULL, 1),
(316, 1, 'Free Body Diagrams #2', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/fbd1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=fbd1', '2017-11-29', 'approved', NULL, 1),
(317, 1, 'Free Body Diagrams #3', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/fbd1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=fbd3', '2017-11-29', 'approved', NULL, 1),
(318, 1, 'Matching Crashes', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/crashes/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=crashes', '2017-11-29', 'approved', NULL, 1),
(319, 1, 'Linear Graphs', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/linear_graphs/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=linear_graphs', '2017-11-29', 'approved', NULL, 1),
(320, 1, 'Significant Figures', 'https://bclearningnetwork.com/LOR/games/balloons/preview.png', 'https://bclearningnetwork.com/LOR/games/balloons/sig_figs.html', '2017-11-29', 'approved', NULL, 1),
(321, 1, 'EM Spectrum', 'https://bclearningnetwork.com/LOR/games/arrange/versions/electromag/preview.png', 'https://bclearningnetwork.com/LOR/games/arrange/arrange.php?title=electromag', '2017-11-29', 'approved', NULL, 1),
(322, 1, 'Right Triangle', 'https://bclearningnetwork.com/LOR/games/potato_categories2/versions/right_triangle/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories2/potato_categories.php?title=right_triangle', '2017-11-29', 'approved', NULL, 1),
(323, 1, 'Polynomials', 'https://bclearningnetwork.com/LOR/games/potato_categories3/versions/polynomials/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories3/potato_categories3.php?title=polynomials', '2017-11-29', 'approved', NULL, 1),
(324, 1, 'Titration', 'https://bclearningnetwork.com/LOR/games/titration/preview.png', 'https://bclearningnetwork.com/LOR/games/titration/index.html', '2017-11-29', 'approved', NULL, 1),
(325, 1, 'Integers', 'https://bclearningnetwork.com/LOR/games/balloons/preview.png', 'https://bclearningnetwork.com/LOR/games/balloons/balloons.php?title=Integers', '2018-01-02', 'approved', NULL, 1),
(326, 1, 'Music', 'https://bclearningnetwork.com/LOR/games/balloons/preview.png', 'https://bclearningnetwork.com/LOR/games/balloons/balloons.php?title=Music', '2018-01-02', 'approved', NULL, 1),
(327, 1, 'Graphing Points', 'https://bclearningnetwork.com/LOR/games/image_labels/versions/graphing_points/preview.png', 'https://bclearningnetwork.com/LOR/games/image_labels/index.php?title=graphing_points', '2018-01-02', 'approved', NULL, 1),
(328, 1, 'BEDMAS', 'https://bclearningnetwork.com/LOR/games/arrange/versions/BEDMAS/preview.png', 'https://bclearningnetwork.com/LOR/games/arrange/arrange.php?title=BEDMAS', '2018-01-02', 'approved', NULL, 1),
(329, 1, 'Physical vs Chemical #1', 'https://bclearningnetwork.com/LOR/games/potato_categories2/versions/phys_chem_changes/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories2/potato_categories.php?title=phys_chem_changes', '2018-01-02', 'approved', NULL, 1),
(330, 1, 'Physical vs Chemical #2', 'https://bclearningnetwork.com/LOR/games/potato_categories2/versions/phys_chem_changes/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories2/potato_categories.php?title=phys_chem_changes2', '2018-01-02', 'approved', NULL, 1),
(331, 1, 'Physical vs Chemical #3', 'https://bclearningnetwork.com/LOR/games/potato_categories2/versions/phys_chem_changes/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories2/potato_categories.php?title=7-changes-1', '2018-01-02', 'approved', NULL, 1),
(332, 1, 'Physical vs Chemical #4', 'https://bclearningnetwork.com/LOR/games/potato_categories2/versions/phys_chem_changes/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories2/potato_categories.php?title=7-changes-2', '2018-01-02', 'approved', NULL, 1),
(333, 1, 'Living vs. Non-Living #1', 'https://bclearningnetwork.com/LOR/games/potato_categories2/versions/living/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories2/potato_categories.php?title=living', '2018-01-02', 'approved', NULL, 1),
(334, 1, 'Living vs. Non-Living #2', 'https://bclearningnetwork.com/LOR/games/potato_categories2/versions/living/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories2/potato_categories.php?title=living2', '2018-01-02', 'approved', NULL, 1),
(335, 1, 'Cells #1', 'https://bclearningnetwork.com/LOR/games/potato_categories3/versions/8-cells-1/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories3/potato_categories3.php?title=8-cells-1', '2018-01-02', 'approved', NULL, 1),
(336, 1, 'Cells #2', 'https://bclearningnetwork.com/LOR/games/potato_categories3/versions/8-cells-1/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories3/potato_categories3.php?title=8-cells-2', '2018-01-02', 'approved', NULL, 1),
(337, 1, 'Addition #1', 'https://bclearningnetwork.com/LOR/games/potato_categories3/versions/addition-1/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories3/potato_categories3.php?title=addition-1', '2018-01-02', 'approved', NULL, 1),
(338, 1, 'Addition #2', 'https://bclearningnetwork.com/LOR/games/potato_categories3/versions/addition-1/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories3/potato_categories3.php?title=addition-2', '2018-01-02', 'approved', NULL, 1),
(339, 1, 'Element, Compound or Mixture', 'https://bclearningnetwork.com/LOR/games/potato_categories3/versions/chem11-category/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories3/potato_categories3.php?title=chem11-category', '2018-01-02', 'approved', NULL, 1),
(340, 1, 'Eco Sort #1', 'https://bclearningnetwork.com/LOR/games/potato_categories3/versions/eco-sort1/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories3/potato_categories3.php?title=eco-sort1', '2018-01-02', 'approved', NULL, 1),
(341, 1, 'Eco Sort #2', 'https://bclearningnetwork.com/LOR/games/potato_categories3/versions/eco-sort1/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories3/potato_categories3.php?title=eco-sort2', '2018-01-02', 'approved', NULL, 1),
(342, 1, 'Rock Cycle', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/5-rockcycle/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=5-rockcycle', '2018-01-02', 'approved', NULL, 1),
(343, 1, 'Algebra Terms', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/Algebra_Terms/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=Algebra_Terms', '2018-01-02', 'approved', NULL, 1),
(344, 1, 'Least Common Multiple', 'https://bclearningnetwork.com/LOR/games/balloons/preview.png', 'https://bclearningnetwork.com/LOR/games/balloons/lcm.html', '2018-01-02', 'approved', NULL, 1),
(345, 1, 'Literary Devices', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/Literary_devices/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=Literary_devices', '2018-01-02', 'approved', NULL, 1),
(346, 1, 'Solar System', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/Solar_system/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=Solar_system', '2018-01-02', 'approved', NULL, 1),
(347, 1, 'WHMIS #1', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/WHMIS-1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=WHMIS-1', '2018-01-02', 'approved', NULL, 1),
(348, 1, 'WHMIS #2', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/WHMIS-1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=WHMIS-2', '2018-01-02', 'approved', NULL, 1),
(349, 1, 'Story Elements #1', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/elements_story1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=elements_story1', '2018-01-02', 'approved', NULL, 1),
(350, 1, 'Story Elements #2', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/elements_story1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=elements_story2', '2018-01-02', 'approved', NULL, 1),
(351, 1, 'Periodic Table #1', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/period_table1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=period_table1', '2018-01-02', 'approved', NULL, 1),
(352, 1, 'Periodic Table #2', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/period_table1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=period_table2', '2018-01-02', 'approved', NULL, 1),
(353, 1, 'Universe', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/universe/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=universe', '2018-01-02', 'approved', NULL, 1),
(354, 1, 'Divisibility', 'https://bclearningnetwork.com/LOR/games/venn_diagram/preview.png', 'https://bclearningnetwork.com/LOR/games/venn_diagram/venn_diagram.php?title=Divisibility', '2018-01-02', 'approved', NULL, 1),
(355, 1, 'Equation or Expression?', 'https://bclearningnetwork.com/LOR/games/potato_categories2/versions/equation_or_expression/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories2/potato_categories.php?title=equation_or_expression', '2018-01-04', 'approved', 'brent@sawatzky.ca', 1),
(356, 1, '1-Step or 2-Step Solving Problem', 'https://bclearningnetwork.com/LOR/games/potato_categories2/versions/1_step_or_2_step/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories2/potato_categories.php?title=1_step_or_2_step', '2018-01-04', 'approved', 'brent@sawatzky.ca', 1),
(357, 1, 'Whack a Mole!', 'https://bclearningnetwork.com/LOR/games/whack_a_mole/preview.png', 'https://bclearningnetwork.com/LOR/games/whack_a_mole/index.html', '2018-01-02', 'approved', NULL, 1),
(358, 1, 'What is Energy?', 'https://bclearningnetwork.com/LOR/media/ph/games/energy1-gameshow/preview.png', 'https://bclearningnetwork.com/LOR/media/ph/games/energy1-gameshow/index.html', '2018-01-04', 'approved', NULL, 1),
(359, 1, 'Energy: Ep & Ek', 'https://bclearningnetwork.com/LOR/media/ph/games/energy2-gameshow/preview.png', 'https://bclearningnetwork.com/LOR/media/ph/games/energy2-gameshow/index.html', '2018-01-04', 'approved', NULL, 1),
(360, 1, 'Bombs', 'https://bclearningnetwork.com/LOR/games/bombs.jpg', 'https://bclearningnetwork.com/LOR/games/bombs.swf', '2018-01-05', 'approved', NULL, 2),
(361, 1, 'Continental Drift', 'https://bclearningnetwork.com/LOR/games/pangaea.jpg', 'https://bclearningnetwork.com/LOR/games/pangaea.swf', '2018-01-05', 'approved', NULL, 2),
(362, 1, 'Food Chains', 'https://bclearningnetwork.com/LOR/games/foodchains.jpg', 'https://bclearningnetwork.com/LOR/games/foodchains.swf', '2018-01-05', 'approved', NULL, 2),
(363, 1, 'Titration', 'https://bclearningnetwork.com/LOR/games/titration.jpg', 'https://bclearningnetwork.com/LOR/games/titration.swf', '2018-01-05', 'approved', NULL, 2),
(364, 1, 'How Fossils Form', 'https://bclearningnetwork.com/LOR/games/fossils.jpg', 'https://bclearningnetwork.com/LOR/games/fossils.swf', '2018-01-05', 'approved', NULL, 2),
(365, 1, 'Arithmetic Avalanche', 'https://bclearningnetwork.com/LOR/games/avalanche.jpg', 'https://bclearningnetwork.com/LOR/games/avalanche.swf', '2018-01-05', 'approved', NULL, 2),
(366, 1, 'Balancing Equations', 'https://bclearningnetwork.com/LOR/games/balance.jpg', 'https://bclearningnetwork.com/LOR/games/balance.swf', '2018-01-05', 'approved', NULL, 2),
(367, 1, 'Using a Bank Card', 'https://bclearningnetwork.com/LOR/games/bankcard.jpg', 'https://bclearningnetwork.com/LOR/games/bankcard.swf', '2018-01-05', 'approved', NULL, 2),
(368, 1, 'Gravity on a Bullet', 'https://bclearningnetwork.com/LOR/games/gravity_gun.jpg', 'https://bclearningnetwork.com/LOR/games/gravity_gun.swf', '2018-01-05', 'approved', NULL, 2),
(369, 1, 'Weathering', 'https://bclearningnetwork.com/LOR/games/weathering.jpg', 'https://bclearningnetwork.com/LOR/games/weathering.swf', '2018-01-05', 'approved', NULL, 2),
(370, 1, 'Vertical Circular Motion', 'https://bclearningnetwork.com/LOR/games/circ_motion_v1.jpg', 'https://bclearningnetwork.com/LOR/games/circ_motion_v1.swf', '2018-01-05', 'approved', NULL, 2),
(371, 1, 'Telephone Banking', 'https://bclearningnetwork.com/LOR/games/telebank.jpg', 'https://bclearningnetwork.com/LOR/games/telebank.swf', '2018-01-05', 'approved', NULL, 2),
(372, 1, 'Vernier Calipers', 'https://bclearningnetwork.com/LOR/games/vernier.jpg', 'https://bclearningnetwork.com/LOR/games/vernier.swf', '2018-01-05', 'approved', NULL, 2),
(373, 1, 'Exponential Applications', 'https://bclearningnetwork.com/LOR/games/caffeine.jpg', 'https://bclearningnetwork.com/LOR/games/caffeine.swf', '2018-01-05', 'approved', NULL, 2),
(374, 1, 'Probabilities - Dice', 'https://bclearningnetwork.com/LOR/games/probdice.jpg', 'https://bclearningnetwork.com/LOR/games/probdice.swf', '2018-01-05', 'approved', NULL, 2),
(375, 1, 'Mutually Exclusive', 'https://bclearningnetwork.com/LOR/games/mutual.jpg', 'https://bclearningnetwork.com/LOR/games/mutual.swf', '2018-01-05', 'approved', NULL, 2),
(376, 1, 'Standard Normal Curve', 'https://bclearningnetwork.com/LOR/games/standnorm.jpg', 'https://bclearningnetwork.com/LOR/games/standnorm.swf', '2018-01-05', 'approved', NULL, 2),
(377, 1, 'Saturated Solutions', 'https://bclearningnetwork.com/LOR/games/saturated.jpg', 'https://bclearningnetwork.com/LOR/games/saturated.swf', '2018-01-05', 'approved', NULL, 2),
(378, 1, 'Collision Theory', 'http://bclearningnetwork.com/LOR/games/collision.jpg', 'https://bclearningnetwork.com/LOR/games/collision.swf', '2018-01-05', 'approved', NULL, 2),
(379, 1, 'Type III Electrolytic Cell', 'https://bclearningnetwork.com/LOR/games/electrolytic2.jpg', 'https://bclearningnetwork.com/LOR/games/electrolytic2.swf', '2018-01-05', 'approved', NULL, 2),
(380, 1, 'Monkey Hunter', 'https://bclearningnetwork.com/LOR/games/monkeyhunter.jpg', 'https://bclearningnetwork.com/LOR/games/monkeyhunter.swf', '2018-01-05', 'approved', NULL, 2),
(381, 1, 'Type 2 Projectile: Golf', 'https://bclearningnetwork.com/LOR/games/golf.jpg', 'https://bclearningnetwork.com/LOR/games/golf.swf', '2018-01-05', 'approved', NULL, 2),
(382, 1, 'Forces on an Incline Plane', 'https://bclearningnetwork.com/LOR/games/incline.jpg', 'https://bclearningnetwork.com/LOR/games/incline.swf', '2018-01-05', 'approved', NULL, 2),
(383, 1, 'Transformer', 'https://bclearningnetwork.com/LOR/games/transformer.jpg', 'https://bclearningnetwork.com/LOR/games/transformer.swf', '2018-01-05', 'approved', NULL, 2),
(384, 1, 'Isometric Dot Paper', 'https://bclearningnetwork.com/LOR/games/iso.jpg', 'https://bclearningnetwork.com/LOR/games/iso.swf', '2018-01-05', 'approved', NULL, 2),
(385, 1, 'Particle Model', 'https://bclearningnetwork.com/LOR/games/partmodel.jpg', 'https://bclearningnetwork.com/LOR/games/partmodel.swf', '2018-01-05', 'approved', NULL, 2),
(386, 1, 'Solubility', 'https://bclearningnetwork.com/LOR/games/solubility.jpg', 'https://bclearningnetwork.com/LOR/games/solubility.swf', '2018-01-05', 'approved', NULL, 2),
(387, 1, 'States of Matter', 'https://bclearningnetwork.com/LOR/games/states.jpg', 'https://bclearningnetwork.com/LOR/games/states.swf', '2018-01-05', 'approved', NULL, 2),
(388, 1, 'Volume', 'https://bclearningnetwork.com/LOR/games/volume.jpg', 'https://bclearningnetwork.com/LOR/games/volume.swf', '2018-01-05', 'approved', NULL, 2),
(389, 1, 'P & S Waves', 'https://bclearningnetwork.com/LOR/games/waves.jpg', 'https://bclearningnetwork.com/LOR/games/waves.swf', '2018-01-05', 'approved', NULL, 2),
(390, 1, 'Regions of France', 'https://bclearningnetwork.com/LOR/games/regions.jpg', 'https://bclearningnetwork.com/LOR/games/regions.swf', '2018-01-05', 'approved', NULL, 2),
(391, 1, 'Venn Diagram', 'https://bclearningnetwork.com/LOR/games/venndiagram.jpg', 'https://bclearningnetwork.com/LOR/games/venndiagram/', '2018-01-05', 'approved', NULL, 2),
(392, 1, 'Justify the Decision', 'https://bclearningnetwork.com/LOR/games/justifythedecision.jpg', 'https://bclearningnetwork.com/LOR/games/justifythedecision/', '2018-01-05', 'approved', NULL, 2),
(393, 1, 'Criteria Pie Chart', 'https://bclearningnetwork.com/LOR/games/piechart.jpg', 'https://bclearningnetwork.com/LOR/games/piechart/', '2018-01-05', 'approved', NULL, 2),
(394, 1, 'Ranking Ladder', 'https://bclearningnetwork.com/LOR/games/rankingladder.jpg', 'https://bclearningnetwork.com/LOR/games/rankingladder/', '2018-01-05', 'approved', NULL, 2),
(395, 1, 'Perfect Squares', 'https://bclearningnetwork.com/LOR/games/invaders/preview.png', 'https://bclearningnetwork.com/LOR/games/invaders/index.php?title=Perfect%20Squares', '2018-01-07', 'approved', 'colinjbernard@hotmail.com', 1),
(396, 1, 'Pangea', 'https://BCLearningNetwork.com/LOR/games/pangea/preview.png', 'https://BCLearningNetwork.com/LOR/games/pangea/pangea.gif', '2018-01-15', 'approved', NULL, 1),
(397, 1, 'Magnetic Striping', 'https://BCLearningNetwork.com/LOR/games/magnetic_flip/preview.png', 'https://BCLearningNetwork.com/LOR/games/magnetic_flip/magnetic.gif', '2018-01-15', 'approved', NULL, 1),
(398, 1, 'Which Level of Government - 1', 'https://bclearningnetwork.com/LOR/games/potato_categories3/versions/government2/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories3/potato_categories3.php?title=government2', '2018-01-22', 'approved', 'bboonstra@sd73.bc.ca', 1),
(399, 1, 'Choose a Prime Number', 'https://bclearningnetwork.com/LOR/games/balloons/preview.png', 'https://bclearningnetwork.com/LOR/games/balloons/balloons.php?title=Prime%20Numbers', '2018-01-23', 'approved', 'bboonstra@sd73.bc.ca', 1),
(400, 1, 'Earthquake', 'https://bclearningnetwork.com/LOR/games/earthquake/preview.png', 'https://bclearningnetwork.com/LOR/games/earthquake/earthquake.html', '2018-01-25', 'approved', NULL, 1),
(401, 1, 'Bobsled Forces', 'https://bclearningnetwork.com/LOR/games/bobsled_forces/preview.png', 'https://bclearningnetwork.com/LOR/games/bobsled_forces/index.html', '2018-01-28', 'approved', NULL, 1),
(402, 1, 'Word Problems 1', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/Word_Problems_1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=Word_Problems_1', '2018-02-05', 'approved', 'brent@sawatzky.ca', 1),
(403, 1, 'Word Problems 2', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/Word_Problems_1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=Word_Problems_2', '2018-02-05', 'approved', 'brent@sawatzky.ca', 1),
(404, 1, 'Canadian Provinces and Geography', 'https://bclearningnetwork.com/LOR/games/canmap/preview.png', 'https://bclearningnetwork.com/LOR/games/canmap/canmap.html', '2018-02-21', 'approved', NULL, 1),
(405, 1, 'Canada\'s National Resources', 'https://bclearningnetwork.com/LOR/games/provres/preview.png', 'https://bclearningnetwork.com/LOR/games/provres/ProvRes.html', '2018-02-21', 'approved', NULL, 1),
(406, 1, 'Bobsled Energy', 'https://bclearningnetwork.com/LOR/games/bobsled_energy/preview.png', 'https://bclearningnetwork.com/LOR/games/bobsled_energy/index.html', '2018-02-21', 'approved', NULL, 1),
(407, 1, 'Perfect Squares', 'https://bclearningnetwork.com/LOR/games/square/preview.png', 'https://bclearningnetwork.com/LOR/games/square/index.html', '2018-02-21', 'approved', NULL, 1),
(408, 1, 'The Respiratory System', 'https://bclearningnetwork.com/LOR/games/respiratory/preview.png', 'https://bclearningnetwork.com/LOR/games/respiratory/respiration.html', '2018-03-01', 'approved', NULL, 1),
(409, 1, 'Dice Simulator', 'https://bclearningnetwork.com/LOR/games/dice/preview.png', 'https://bclearningnetwork.com/LOR/games/dice/index.html', '2018-03-02', 'approved', NULL, 1),
(410, 1, 'Vertical Line Test', 'https://bclearningnetwork.com/LOR/games/vertical/preview.png', 'https://bclearningnetwork.com/LOR/games/vertical/vlt.html', '2018-03-15', 'approved', NULL, 1),
(411, 1, 'Relative Velocity', 'https://bclearningnetwork.com/LOR/games/relativevelocity/preview.png', 'https://bclearningnetwork.com/LOR/games/relativevelocity/relativevelocity.html', '2018-03-15', 'approved', NULL, 1),
(412, 2, 'How do scientists estimate the number of fish in a lake?', 'https://bclearningnetwork.com/LOR/projects/M9U02P02.png', 'https://bclearningnetwork.com/LOR/projects/M9U02P02.pdf', '2018-02-21', 'approved', NULL, NULL),
(413, 2, 'You live in a safe and quiet neighborhood.  Unfortunately, somebody has decided that your street is perfect for drag racing and you need evidence.  Armed with a cell phone and some solid physics you record the motion of this vehicle.  ', 'https://bclearningnetwork.com/LOR/projects/P11U02P03.png', 'https://bclearningnetwork.com/LOR/projects/P11U02P03.pdf', '2018-03-12', 'approved', NULL, NULL),
(414, 2, 'How do CS investigators use skid marks to analyze an accident scene?', 'https://bclearningnetwork.com/LOR/projects/P11U03P02.png', 'https://bclearningnetwork.com/LOR/projects/P11U03P02.pdf', '2018-02-17', 'approved', NULL, NULL),
(415, 2, 'How much energy is used by different appliances when accomplishing the same task?', 'https://bclearningnetwork.com/LOR/projects/P11U05P04.png', 'https://bclearningnetwork.com/LOR/projects/P11U05P04.pdf', '2018-02-17', 'approved', NULL, NULL),
(416, 2, 'In some movies, a spy or agent is transported with a blindfold, but they still determine where they were taken. Is this really possible?', 'https://bclearningnetwork.com/LOR/projects/P12U01P02.png', 'https://bclearningnetwork.com/LOR/projects/P12U01P02.pdf', '2018-02-17', 'approved', NULL, NULL),
(417, 2, 'How can we build a model to better visualize how our circulatory system works?', 'https://bclearningnetwork.com/LOR/projects/SC5U01P01.png', 'https://bclearningnetwork.com/LOR/projects/SC5U01P01.pdf', '2018-02-21', 'approved', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lor_content_categories`
--

CREATE TABLE `mdl_lor_content_categories` (
  `content` bigint(20) NOT NULL,
  `category` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mdl_lor_content_categories`
--

INSERT INTO `mdl_lor_content_categories` (`content`, `category`) VALUES
(312, 2),
(313, 2),
(314, 5),
(315, 7),
(316, 5),
(317, 5),
(318, 5),
(319, 5),
(320, 2),
(321, 5),
(322, 2),
(323, 2),
(324, 4),
(325, 2),
(326, 8),
(327, 2),
(328, 2),
(329, 4),
(330, 4),
(331, 4),
(332, 4),
(333, 3),
(334, 3),
(335, 7),
(336, 7),
(337, 2),
(338, 2),
(339, 4),
(340, 7),
(341, 7),
(342, 3),
(343, 2),
(344, 2),
(345, 6),
(346, 3),
(347, 8),
(348, 8),
(349, 6),
(350, 6),
(351, 4),
(352, 4),
(353, 3),
(354, 2),
(355, 2),
(356, 2),
(357, 2),
(358, 5),
(359, 5),
(360, 2),
(361, 3),
(362, 7),
(363, 4),
(364, 3),
(365, 2),
(366, 2),
(367, 8),
(368, 5),
(369, 3),
(370, 5),
(371, 8),
(372, 8),
(373, 2),
(374, 2),
(375, 2),
(376, 2),
(377, 4),
(378, 4),
(379, 4),
(380, 8),
(381, 5),
(382, 5),
(383, 5),
(384, 8),
(385, 4),
(386, 4),
(387, 4),
(388, 2),
(389, 3),
(390, 8),
(391, 2),
(392, 8),
(393, 8),
(394, 8),
(395, 2),
(396, 3),
(397, 3),
(398, 8),
(399, 2),
(400, 3),
(401, 5),
(402, 2),
(403, 2),
(404, 8),
(405, 8),
(406, 5),
(407, 2),
(408, 7),
(409, 2),
(410, 2),
(411, 5);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lor_content_contributors`
--

CREATE TABLE `mdl_lor_content_contributors` (
  `content` bigint(20) NOT NULL,
  `contributor` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lor_content_grades`
--

CREATE TABLE `mdl_lor_content_grades` (
  `content` bigint(20) NOT NULL,
  `grade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lor_content_keywords`
--

CREATE TABLE `mdl_lor_content_keywords` (
  `content` bigint(20) NOT NULL,
  `keyword` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mdl_lor_content_keywords`
--

INSERT INTO `mdl_lor_content_keywords` (`content`, `keyword`) VALUES
(412, 'Math'),
(412, 'Science');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lor_contributor`
--

CREATE TABLE `mdl_lor_contributor` (
  `id` bigint(20) NOT NULL,
  `name` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lor_game`
--

CREATE TABLE `mdl_lor_game` (
  `id` bigint(20) NOT NULL,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_created` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cid` bigint(20) NOT NULL,
  `pid` bigint(20) NOT NULL,
  `status` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_email` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mdl_lor_game`
--

INSERT INTO `mdl_lor_game` (`id`, `title`, `description`, `image`, `link`, `date_created`, `author`, `cid`, `pid`, `status`, `author_email`) VALUES
(1, 'Multiplication Balloons', 'In this game you will be given numbers at the top of the screen which you will have to multiply together. Choose the balloon with the correct result.', 'https://bclearningnetwork.com/LOR/games/balloons/preview.png', 'https://bclearningnetwork.com/LOR/games/balloons/multiplication.html', '20170620', 'BCLN', 2, 1, 'approved', NULL),
(2, 'Greatest Common Factor', 'The player is presented with an expression and must choose the correct greatest common factor from several balloons with options. Try to reach a new high score!', 'https://bclearningnetwork.com/LOR/games/balloons/preview.png', 'https://bclearningnetwork.com/LOR/games/balloons/balloons.php?title=Greatest Common Factor', '20170601', 'BCLN', 2, 1, 'approved', NULL),
(4, 'Free Body Diagrams #1', 'Practice physics free body diagrams with this matching game.', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/fbd1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=fbd1', '20170501', 'Brent Sawatzky', 5, 1, 'approved', NULL),
(5, 'Food Chains', 'Arrange four organisms in the order they appear in the food chain.', 'https://bclearningnetwork.com/LOR/games/arrange/versions/foodchains/preview.png', 'https://bclearningnetwork.com/LOR/games/arrange/arrange.php?title=foodchains', '20170401', 'BCLN', 7, 1, 'approved', NULL),
(6, 'Free Body Diagrams #2', 'Match each free body diagram.', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/fbd1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=fbd1', '20171129', 'Brent Sawatzky', 5, 1, 'approved', NULL),
(7, 'Free Body Diagrams #3', 'Match each free body diagram to a description.', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/fbd1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=fbd3', '20171129', 'Brent Sawatzky', 5, 1, 'approved', NULL),
(8, 'Matching Crashes', 'Match each description to a crash equation.', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/crashes/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=crashes', '20171129', 'Brent Sawatzky', 5, 1, 'approved', NULL),
(9, 'Linear Graphs', 'Match each linear graph with an equation.', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/linear_graphs/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=linear_graphs', '20171129', 'Brent Sawatzky', 5, 1, 'approved', NULL),
(10, 'Significant Figures', 'In this game you will be given a number at the top of the screen. Your job is to choose the correct amount of significant figures of that number from the balloons drifting upwards.', 'https://bclearningnetwork.com/LOR/games/balloons/preview.png', 'https://bclearningnetwork.com/LOR/games/balloons/sig_figs.html', '20171129', 'BCLN', 2, 1, 'approved', NULL),
(11, 'EM Spectrum', 'Drag the items on the left into the correct order on the right.', 'https://bclearningnetwork.com/LOR/games/arrange/versions/electromag/preview.png', 'https://bclearningnetwork.com/LOR/games/arrange/arrange.php?title=electromag', '20171129', 'BCLN', 5, 1, 'approved', NULL),
(12, 'Right Triangle', 'Given a triangle, decide whether it is a right triangle or not a right triangle by dragging the given image to the correct category.', 'https://bclearningnetwork.com/LOR/games/potato_categories2/versions/right_triangle/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories2/potato_categories.php?title=right_triangle', '20171129', 'BCLN', 2, 1, 'approved', NULL),
(13, 'Polynomials', 'Drag each expression into one of three categories. It may be a monomial, binomial or a trinomial.', 'https://bclearningnetwork.com/LOR/games/potato_categories3/versions/polynomials/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories3/potato_categories3.php?title=polynomials', '20171129', 'BCLN', 2, 1, 'approved', NULL),
(14, 'Titration', 'Practice performing a titration in a laboratory setting.', 'https://bclearningnetwork.com/LOR/games/titration/preview.png', 'https://bclearningnetwork.com/LOR/games/titration/index.html', '20171129', 'BCLN', 4, 1, 'approved', NULL),
(22, 'Integers', 'A variety of multiplying, adding, and subtracting of integers.', 'https://bclearningnetwork.com/LOR/games/balloons/preview.png', 'https://bclearningnetwork.com/LOR/games/balloons/balloons.php?title=Integers', '20180102', 'Brent Sawatzky', 2, 1, 'approved', NULL),
(23, 'Music', 'Match the words.', 'https://bclearningnetwork.com/LOR/games/balloons/preview.png', 'https://bclearningnetwork.com/LOR/games/balloons/balloons.php?title=Music', '20180102', 'BCLN', 8, 1, 'approved', NULL),
(24, 'Graphing Points', 'Choose the correct point on an X-Y Plot', 'https://bclearningnetwork.com/LOR/games/image_labels/versions/graphing_points/preview.png', 'https://bclearningnetwork.com/LOR/games/image_labels/index.php?title=graphing_points', '20180102', 'Brent Sawatzky', 2, 1, 'approved', NULL),
(25, 'BEDMAS', 'BEDMAS is an acronym to help remember the order of operations when evaluating problems with several different operations.', 'https://bclearningnetwork.com/LOR/games/arrange/versions/BEDMAS/preview.png', 'https://bclearningnetwork.com/LOR/games/arrange/arrange.php?title=BEDMAS', '20180102', 'BCLN', 2, 1, 'approved', NULL),
(26, 'Physical vs Chemical #1', 'Physical vs chemical changes.', 'https://bclearningnetwork.com/LOR/games/potato_categories2/versions/phys_chem_changes/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories2/potato_categories.php?title=phys_chem_changes', '20180102', 'BCLN', 4, 1, 'approved', NULL),
(27, 'Physical vs Chemical #2', 'Physical vs chemical changes.', 'https://bclearningnetwork.com/LOR/games/potato_categories2/versions/phys_chem_changes/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories2/potato_categories.php?title=phys_chem_changes2', '20180102', 'BCLN', 4, 1, 'approved', NULL),
(28, 'Physical vs Chemical #3', 'Physical vs chemical changes.', 'https://bclearningnetwork.com/LOR/games/potato_categories2/versions/phys_chem_changes/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories2/potato_categories.php?title=7-changes-1', '20180102', 'BCLN', 4, 1, 'approved', NULL),
(29, 'Physical vs Chemical #4', 'Physical vs chemical changes.', 'https://bclearningnetwork.com/LOR/games/potato_categories2/versions/phys_chem_changes/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories2/potato_categories.php?title=7-changes-2', '20180102', 'BCLN', 4, 1, 'approved', NULL),
(30, 'Living vs. Non-Living #1', 'Categorise different objects as being living or as being non-living.', 'https://bclearningnetwork.com/LOR/games/potato_categories2/versions/living/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories2/potato_categories.php?title=living', '20180102', 'BCLN', 3, 1, 'approved', NULL),
(31, 'Living vs. Non-Living #2', 'Categorise different objects as being living or as being non-living.', 'https://bclearningnetwork.com/LOR/games/potato_categories2/versions/living/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories2/potato_categories.php?title=living2', '20180102', 'BCLN', 3, 1, 'approved', NULL),
(32, 'Cells #1', 'Categorize parts of the cell as being in \"only plant cells\", \"plant and animal cells\" or \"only animal cells\".', 'https://bclearningnetwork.com/LOR/games/potato_categories3/versions/8-cells-1/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories3/potato_categories3.php?title=8-cells-1', '20180102', 'BCLN', 7, 1, 'approved', NULL),
(33, 'Cells #2', 'Categorize parts of the cell as being in \"only plant cells\", \"plant and animal cells\" or \"only animal cells\".', 'https://bclearningnetwork.com/LOR/games/potato_categories3/versions/8-cells-1/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories3/potato_categories3.php?title=8-cells-2', '20180102', 'BCLN', 7, 1, 'approved', NULL),
(34, 'Addition #1', 'Decide whether an expression will evaluate to a positive number, negative number, or to zero.', 'https://bclearningnetwork.com/LOR/games/potato_categories3/versions/addition-1/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories3/potato_categories3.php?title=addition-1', '20180102', 'BCLN', 2, 1, 'approved', NULL),
(35, 'Addition #2', 'Decide whether an expression will evaluate to a positive number, negative number, or to zero.', 'https://bclearningnetwork.com/LOR/games/potato_categories3/versions/addition-1/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories3/potato_categories3.php?title=addition-2', '20180102', 'BCLN', 2, 1, 'approved', NULL),
(36, 'Element, Compound or Mixture', 'Decide whether a given \'something\' is an element, a compound or a mixture. ', 'https://bclearningnetwork.com/LOR/games/potato_categories3/versions/chem11-category/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories3/potato_categories3.php?title=chem11-category', '20180102', 'BCLN', 4, 1, 'approved', NULL),
(37, 'Eco Sort #1', 'Categorize an object as a micro-organism, non-living, or as an organism.', 'https://bclearningnetwork.com/LOR/games/potato_categories3/versions/eco-sort1/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories3/potato_categories3.php?title=eco-sort1', '20180102', 'BCLN', 7, 1, 'approved', NULL),
(38, 'Eco Sort #2', 'Categorize an object as a micro-organism, non-living, or as an organism.', 'https://bclearningnetwork.com/LOR/games/potato_categories3/versions/eco-sort1/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories3/potato_categories3.php?title=eco-sort2', '20180102', 'BCLN', 7, 1, 'approved', NULL),
(39, 'Rock Cycle', 'Match each box on the left with a box on the right.', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/5-rockcycle/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=5-rockcycle', '20180102', 'BCLN', 3, 1, 'approved', NULL),
(40, 'Algebra Terms', 'Match each algebra term with its symbol.', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/Algebra_Terms/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=Algebra_Terms', '20180102', 'BCLN', 2, 1, 'approved', NULL),
(41, 'Least Common Multiple', 'In this game you will be given two numbers at the top of the screen. Your job is to choose the Least Common Multiple from the balloons drifting upwards.', 'https://bclearningnetwork.com/LOR/games/balloons/preview.png', 'https://bclearningnetwork.com/LOR/games/balloons/lcm.html', '20180102', 'BCLN', 2, 1, 'approved', NULL),
(42, 'Literary Devices', 'Match each literary device with an example.', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/Literary_devices/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=Literary_devices', '20180102', 'BCLN', 6, 1, 'approved', NULL),
(43, 'Solar System', 'Match each term with each definition.', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/Solar_system/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=Solar_system', '20180102', 'BCLN', 3, 1, 'approved', NULL),
(44, 'WHMIS #1', 'Workplace Hazardous Materials Information System', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/WHMIS-1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=WHMIS-1', '20180102', 'BCLN', 8, 1, 'approved', NULL),
(45, 'WHMIS #2', 'Workplace Hazardous Materials Information System', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/WHMIS-1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=WHMIS-2', '20180102', 'BCLN', 8, 1, 'approved', NULL),
(46, 'Story Elements #1', 'Match each story element term with its corresponding definition.', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/elements_story1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=elements_story1', '20180102', 'BCLN', 6, 1, 'approved', NULL),
(47, 'Story Elements #2', 'Match each story element term with its corresponding definition.', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/elements_story1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=elements_story2', '20180102', 'BCLN', 6, 1, 'approved', NULL),
(48, 'Periodic Table #1', 'Match sections of the period table to their descriptions.', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/period_table1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=period_table1', '20180102', 'BCLN', 4, 1, 'approved', NULL),
(49, 'Periodic Table #2', 'Match sections of the period table to their descriptions.', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/period_table1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=period_table2', '20180102', 'BCLN', 4, 1, 'approved', NULL),
(50, 'Universe', 'Match each term with its definition.', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/universe/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=universe', '20180102', 'BCLN', 3, 1, 'approved', NULL),
(51, 'Divisibility', 'Sort numbers by divisibility using a venn diagram.', 'https://bclearningnetwork.com/LOR/games/venn_diagram/preview.png', 'https://bclearningnetwork.com/LOR/games/venn_diagram/venn_diagram.php?title=Divisibility', '20180102', 'BCLN', 2, 1, 'approved', NULL),
(52, 'Equation or Expression?', 'Math / Algebra for Solving.', 'https://bclearningnetwork.com/LOR/games/potato_categories2/versions/equation_or_expression/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories2/potato_categories.php?title=equation_or_expression', '20180104', 'Brent Sawatzky', 2, 1, 'approved', 'brent@sawatzky.ca'),
(53, '1-Step or 2-Step Solving Problem', 'For Math-Algebra solving.', 'https://bclearningnetwork.com/LOR/games/potato_categories2/versions/1_step_or_2_step/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories2/potato_categories.php?title=1_step_or_2_step', '20180104', 'Brent Sawatzky', 2, 1, 'approved', 'brent@sawatzky.ca'),
(54, 'Whack a Mole!', 'Click the correct coordinates to hit the mole.', 'https://bclearningnetwork.com/LOR/games/whack_a_mole/preview.png', 'https://bclearningnetwork.com/LOR/games/whack_a_mole/index.html', '20180102', 'BCLN', 2, 1, 'approved', NULL),
(55, 'Test', 'Test Desc', NULL, 'https://bclearningnetwork.com/LOR/games/venn_diagram/venn_diagram.php?title=test11', '20180104', 'Colin Bernard', 2, 1, 'rejected', 'colinjbernard@hotmail.com'),
(56, 'What is Energy?', 'Interactive physics game show.', 'https://bclearningnetwork.com/LOR/media/ph/games/energy1-gameshow/preview.png', 'https://bclearningnetwork.com/LOR/media/ph/games/energy1-gameshow/index.html', '20180104', 'BCLN', 5, 1, 'approved', NULL),
(57, 'Energy: Ep & Ek', 'Interactive physics game show.', 'https://bclearningnetwork.com/LOR/media/ph/games/energy2-gameshow/preview.png', 'https://bclearningnetwork.com/LOR/media/ph/games/energy2-gameshow/index.html', '20180104', 'BCLN', 5, 1, 'approved', NULL),
(58, 'Bombs', 'Save the penguins by detonating the bomb before it hits the ground. ', 'https://bclearningnetwork.com/LOR/games/bombs.jpg', 'https://bclearningnetwork.com/LOR/games/bombs.swf', '20180105', 'BCLN', 2, 2, 'approved', NULL),
(59, 'Continental Drift', 'An animation of continental drift.', 'https://bclearningnetwork.com/LOR/games/pangaea.jpg', 'https://bclearningnetwork.com/LOR/games/pangaea.swf', '20180105', 'BCLN', 3, 2, 'approved', NULL),
(60, 'Food Chains', 'Order the images correctly.', 'https://bclearningnetwork.com/LOR/games/foodchains.jpg', 'https://bclearningnetwork.com/LOR/games/foodchains.swf', '20180105', 'BCLN', 7, 2, 'approved', NULL),
(61, 'Titration', 'Perform a titration.', 'https://bclearningnetwork.com/LOR/games/titration.jpg', 'https://bclearningnetwork.com/LOR/games/titration.swf', '20180105', 'BCLN', 4, 2, 'approved', NULL),
(62, 'How Fossils Form', 'Explores terms such as trace, casts, carbonization, replacement, and permineralization.', 'https://bclearningnetwork.com/LOR/games/fossils.jpg', 'https://bclearningnetwork.com/LOR/games/fossils.swf', '20180105', 'BCLN', 3, 2, 'approved', NULL),
(63, 'Arithmetic Avalanche', 'Claudio, the CoolSchool Penguin has triggered an Arithmetic Avalanche by climbing too high up the mountain. Help him get away by solving some arithmetic sequences.', 'https://bclearningnetwork.com/LOR/games/avalanche.jpg', 'https://bclearningnetwork.com/LOR/games/avalanche.swf', '20180105', 'BCLN', 2, 2, 'approved', NULL),
(64, 'Balancing Equations', 'Balance an equation.', 'https://bclearningnetwork.com/LOR/games/balance.jpg', 'https://bclearningnetwork.com/LOR/games/balance.swf', '20180105', 'BCLN', 2, 2, 'approved', NULL),
(65, 'Using a Bank Card', 'Learn to use an ATM.', 'https://bclearningnetwork.com/LOR/games/bankcard.jpg', 'https://bclearningnetwork.com/LOR/games/bankcard.swf', '20180105', 'BCLN', 8, 2, 'approved', NULL),
(66, 'Gravity on a Bullet', 'Adjust the muzzle velocity and observe changes.', 'https://bclearningnetwork.com/LOR/games/gravity_gun.jpg', 'https://bclearningnetwork.com/LOR/games/gravity_gun.swf', '20180105', 'BCLN', 5, 2, 'approved', NULL),
(67, 'Weathering', 'Match the terms and images.', 'https://bclearningnetwork.com/LOR/games/weathering.jpg', 'https://bclearningnetwork.com/LOR/games/weathering.swf', '20180105', 'BCLN', 3, 2, 'approved', NULL),
(68, 'Vertical Circular Motion', NULL, 'https://bclearningnetwork.com/LOR/games/circ_motion_v1.jpg', 'https://bclearningnetwork.com/LOR/games/circ_motion_v1.swf', '20180105', 'BCLN', 5, 2, 'approved', NULL),
(69, 'Telephone Banking', 'Learn to bank on your telephone.', 'https://bclearningnetwork.com/LOR/games/telebank.jpg', 'https://bclearningnetwork.com/LOR/games/telebank.swf', '20180105', 'BCLN', 8, 2, 'approved', NULL),
(70, 'Vernier Calipers', NULL, 'https://bclearningnetwork.com/LOR/games/vernier.jpg', 'https://bclearningnetwork.com/LOR/games/vernier.swf', '20180105', 'BCLN', 8, 2, 'approved', NULL),
(71, 'Exponential Applications', NULL, 'https://bclearningnetwork.com/LOR/games/caffeine.jpg', 'https://bclearningnetwork.com/LOR/games/caffeine.swf', '20180105', 'BCLN', 2, 2, 'approved', NULL),
(72, 'Probabilities - Dice', NULL, 'https://bclearningnetwork.com/LOR/games/probdice.jpg', 'https://bclearningnetwork.com/LOR/games/probdice.swf', '20180105', 'BCLN', 2, 2, 'approved', NULL),
(73, 'Mutually Exclusive', NULL, 'https://bclearningnetwork.com/LOR/games/mutual.jpg', 'https://bclearningnetwork.com/LOR/games/mutual.swf', '20180105', 'BCLN', 2, 2, 'approved', NULL),
(74, 'Standard Normal Curve', NULL, 'https://bclearningnetwork.com/LOR/games/standnorm.jpg', 'https://bclearningnetwork.com/LOR/games/standnorm.swf', '20180105', 'BCLN', 2, 2, 'approved', NULL),
(75, 'Saturated Solutions', NULL, 'https://bclearningnetwork.com/LOR/games/saturated.jpg', 'https://bclearningnetwork.com/LOR/games/saturated.swf', '20180105', 'BCLN', 4, 2, 'approved', NULL),
(76, 'Collision Theory', NULL, 'http://bclearningnetwork.com/LOR/games/collision.jpg', 'https://bclearningnetwork.com/LOR/games/collision.swf', '20180105', 'BCLN', 4, 2, 'approved', NULL),
(77, 'Type III Electrolytic Cell', NULL, 'https://bclearningnetwork.com/LOR/games/electrolytic2.jpg', 'https://bclearningnetwork.com/LOR/games/electrolytic2.swf', '20180105', 'BCLN', 4, 2, 'approved', NULL),
(78, 'Monkey Hunter', NULL, 'https://bclearningnetwork.com/LOR/games/monkeyhunter.jpg', 'https://bclearningnetwork.com/LOR/games/monkeyhunter.swf', '20180105', 'BCLN', 8, 2, 'approved', NULL),
(79, 'Type 2 Projectile: Golf', NULL, 'https://bclearningnetwork.com/LOR/games/golf.jpg', 'https://bclearningnetwork.com/LOR/games/golf.swf', '20180105', 'BCLN', 5, 2, 'approved', NULL),
(80, 'Forces on an Incline Plane', NULL, 'https://bclearningnetwork.com/LOR/games/incline.jpg', 'https://bclearningnetwork.com/LOR/games/incline.swf', '20180105', 'BCLN', 5, 2, 'approved', NULL),
(81, 'Transformer', NULL, 'https://bclearningnetwork.com/LOR/games/transformer.jpg', 'https://bclearningnetwork.com/LOR/games/transformer.swf', '20180105', 'BCLN', 5, 2, 'approved', NULL),
(82, 'Isometric Dot Paper', 'Click from dot to dot to create objects.', 'https://bclearningnetwork.com/LOR/games/iso.jpg', 'https://bclearningnetwork.com/LOR/games/iso.swf', '20180105', 'BCLN', 8, 2, 'approved', NULL),
(83, 'Particle Model', NULL, 'https://bclearningnetwork.com/LOR/games/partmodel.jpg', 'https://bclearningnetwork.com/LOR/games/partmodel.swf', '20180105', 'BCLN', 4, 2, 'approved', NULL),
(84, 'Solubility', NULL, 'https://bclearningnetwork.com/LOR/games/solubility.jpg', 'https://bclearningnetwork.com/LOR/games/solubility.swf', '20180105', 'BCLN', 4, 2, 'approved', NULL),
(85, 'States of Matter', NULL, 'https://bclearningnetwork.com/LOR/games/states.jpg', 'https://bclearningnetwork.com/LOR/games/states.swf', '20180105', 'BCLN', 4, 2, 'approved', NULL),
(86, 'Volume', NULL, 'https://bclearningnetwork.com/LOR/games/volume.jpg', 'https://bclearningnetwork.com/LOR/games/volume.swf', '20180105', 'BCLN', 2, 2, 'approved', NULL),
(87, 'P & S Waves', NULL, 'https://bclearningnetwork.com/LOR/games/waves.jpg', 'https://bclearningnetwork.com/LOR/games/waves.swf', '20180105', 'BCLN', 3, 2, 'approved', NULL),
(88, 'Regions of France', NULL, 'https://bclearningnetwork.com/LOR/games/regions.jpg', 'https://bclearningnetwork.com/LOR/games/regions.swf', '20180105', 'BCLN', 8, 2, 'approved', NULL),
(89, 'Venn Diagram', NULL, 'https://bclearningnetwork.com/LOR/games/venndiagram.jpg', 'https://bclearningnetwork.com/LOR/games/venndiagram/', '20180105', 'BCLN', 2, 2, 'approved', NULL),
(90, 'Justify the Decision', NULL, 'https://bclearningnetwork.com/LOR/games/justifythedecision.jpg', 'https://bclearningnetwork.com/LOR/games/justifythedecision/', '20180105', 'BCLN', 8, 2, 'approved', NULL),
(91, 'Criteria Pie Chart', NULL, 'https://bclearningnetwork.com/LOR/games/piechart.jpg', 'https://bclearningnetwork.com/LOR/games/piechart/', '20180105', 'BCLN', 8, 2, 'approved', NULL),
(92, 'Ranking Ladder', NULL, 'https://bclearningnetwork.com/LOR/games/rankingladder.jpg', 'https://bclearningnetwork.com/LOR/games/rankingladder/', '20180105', 'BCLN', 8, 2, 'approved', NULL),
(93, 'Perfect Squares', 'Defend the planet by computing the square or square root correctly!', 'https://bclearningnetwork.com/LOR/games/invaders/preview.png', 'https://bclearningnetwork.com/LOR/games/invaders/index.php?title=Perfect%20Squares', '20180107', 'Colin Bernard', 2, 1, 'approved', 'colinjbernard@hotmail.com'),
(94, 'Pangea', 'Short GIF animation of continental drift.', 'https://BCLearningNetwork.com/LOR/games/pangea/preview.png', 'https://BCLearningNetwork.com/LOR/games/pangea/pangea.gif', '20180115', 'Brent Sawatzky', 3, 1, 'approved', NULL),
(95, 'Magnetic Striping', 'GIF animation showcasing magnetic striping.', 'https://BCLearningNetwork.com/LOR/games/magnetic_flip/preview.png', 'https://BCLearningNetwork.com/LOR/games/magnetic_flip/magnetic.gif', '20180115', 'Brent Sawatzky', 3, 1, 'approved', NULL),
(96, 'Which Level of Government - 1', 'Choose the correct level of government', 'https://bclearningnetwork.com/LOR/games/potato_categories3/versions/government2/preview.png', 'https://bclearningnetwork.com/LOR/games/potato_categories3/potato_categories3.php?title=government2', '20180122', 'Barbara Boonstra', 8, 1, 'approved', 'bboonstra@sd73.bc.ca'),
(97, 'Choose a Prime Number', 'Choose a prime number up to 100.', 'https://bclearningnetwork.com/LOR/games/balloons/preview.png', 'https://bclearningnetwork.com/LOR/games/balloons/balloons.php?title=Prime%20Numbers', '20180123', 'Barbara Boonstra', 2, 1, 'approved', 'bboonstra@sd73.bc.ca'),
(98, 'Earthquake', 'Examine the different wave types resulting from an earthquake.', 'https://bclearningnetwork.com/LOR/games/earthquake/preview.png', 'https://bclearningnetwork.com/LOR/games/earthquake/earthquake.html', '20180125', 'Brittany Miller', 3, 1, 'approved', NULL),
(99, 'Bobsled Forces', 'Adjust the mass, push force, surface, and position to achieve the best bobsled distance!', 'https://bclearningnetwork.com/LOR/games/bobsled_forces/preview.png', 'https://bclearningnetwork.com/LOR/games/bobsled_forces/index.html', '20180128', 'Colin Bernard', 5, 1, 'approved', NULL),
(100, 'Word Problems 1', 'Math word problem terms.', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/Word_Problems_1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=Word_Problems_1', '20180205', 'Brent', 2, 1, 'approved', 'brent@sawatzky.ca'),
(101, 'Word Problems 2', 'Math word problem terms.', 'https://bclearningnetwork.com/LOR/games/spiderlove/versions/Word_Problems_1/preview.png', 'https://bclearningnetwork.com/LOR/games/spiderlove/spiders.php?title=Word_Problems_2', '20180205', 'Brent', 2, 1, 'approved', 'brent@sawatzky.ca'),
(102, 'Canadian Provinces and Geography', 'Explore Canadian geographical divisions as well as provincial divisions.', 'https://bclearningnetwork.com/LOR/games/canmap/preview.png', 'https://bclearningnetwork.com/LOR/games/canmap/canmap.html', '20180221', 'Brittany Miller', 8, 1, 'approved', NULL),
(103, 'Canada\'s National Resources', 'Across the country, Canada has several different natural resources. Each province\'s and territory\'s economy is linked to the availability and harvesting of these resources for use within Canada, or for the export to other countries.', 'https://bclearningnetwork.com/LOR/games/provres/preview.png', 'https://bclearningnetwork.com/LOR/games/provres/ProvRes.html', '20180221', 'Brittany Miller', 8, 1, 'approved', NULL),
(104, 'Bobsled Energy', 'Adjust the mass of a bobsled team, the height of a ramp, the type of surface, and the position of the bobsled team to achieve the best bobsled distance!', 'https://bclearningnetwork.com/LOR/games/bobsled_energy/preview.png', 'https://bclearningnetwork.com/LOR/games/bobsled_energy/index.html', '20180221', 'Colin Bernard', 5, 1, 'approved', NULL),
(105, 'Perfect Squares', 'Drag the black dot to adjust the values of the perfect squares. How does it affect the result?', 'https://bclearningnetwork.com/LOR/games/square/preview.png', 'https://bclearningnetwork.com/LOR/games/square/index.html', '20180221', 'Colin Bernard, Brittany Miller', 2, 1, 'approved', NULL),
(106, 'The Respiratory System', 'Drag terms to the correct label on the diagram.', 'https://bclearningnetwork.com/LOR/games/respiratory/preview.png', 'https://bclearningnetwork.com/LOR/games/respiratory/respiration.html', '20180301', 'Brent Sawatzky', 7, 1, 'approved', NULL),
(107, 'Dice Simulator', 'Visualise the probabilities of rolling two dice on a column chart.', 'https://bclearningnetwork.com/LOR/games/dice/preview.png', 'https://bclearningnetwork.com/LOR/games/dice/index.html', '20180302', 'Colin Bernard', 2, 1, 'approved', NULL),
(108, 'Vertical Line Test', 'A visual way to determine if a curve is a graph of a function or not.', 'https://bclearningnetwork.com/LOR/games/vertical/preview.png', 'https://bclearningnetwork.com/LOR/games/vertical/vlt.html', '20180315', 'Brittany Miller', 2, 1, 'approved', NULL),
(109, 'Relative Velocity', 'Cross the river while controlling the boat\'s throttle and the river current.', 'https://bclearningnetwork.com/LOR/games/relativevelocity/preview.png', 'https://bclearningnetwork.com/LOR/games/relativevelocity/relativevelocity.html', '20180315', 'Eric Nelson', 5, 1, 'approved', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lor_grade`
--

CREATE TABLE `mdl_lor_grade` (
  `grade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mdl_lor_grade`
--

INSERT INTO `mdl_lor_grade` (`grade`) VALUES
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12);

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lor_keyword`
--

CREATE TABLE `mdl_lor_keyword` (
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mdl_lor_keyword`
--

INSERT INTO `mdl_lor_keyword` (`name`) VALUES
('Acceleration'),
('Biology'),
('Efficiency'),
('Energy'),
('Environmental'),
('Friction Forces'),
('Graphs'),
('Kinematics'),
('Math'),
('Power'),
('Statistics'),
('Velocity');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lor_platform`
--

CREATE TABLE `mdl_lor_platform` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mdl_lor_platform`
--

INSERT INTO `mdl_lor_platform` (`id`, `name`) VALUES
(1, 'HTML5'),
(2, 'Flash');

-- --------------------------------------------------------

--
-- Table structure for table `mdl_lor_type`
--

CREATE TABLE `mdl_lor_type` (
  `id` bigint(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mdl_lor_type`
--

INSERT INTO `mdl_lor_type` (`id`, `name`) VALUES
(1, 'Game'),
(2, 'Project'),
(3, 'Video');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mdl_lor_category`
--
ALTER TABLE `mdl_lor_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mdl_lor_content`
--
ALTER TABLE `mdl_lor_content`
  ADD PRIMARY KEY (`id`),
  ADD KEY `platform` (`platform`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `mdl_lor_content_categories`
--
ALTER TABLE `mdl_lor_content_categories`
  ADD PRIMARY KEY (`content`,`category`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `mdl_lor_content_contributors`
--
ALTER TABLE `mdl_lor_content_contributors`
  ADD PRIMARY KEY (`content`,`contributor`),
  ADD KEY `contributor` (`contributor`);

--
-- Indexes for table `mdl_lor_content_grades`
--
ALTER TABLE `mdl_lor_content_grades`
  ADD PRIMARY KEY (`content`,`grade`),
  ADD KEY `grade` (`grade`);

--
-- Indexes for table `mdl_lor_content_keywords`
--
ALTER TABLE `mdl_lor_content_keywords`
  ADD PRIMARY KEY (`content`,`keyword`);

--
-- Indexes for table `mdl_lor_contributor`
--
ALTER TABLE `mdl_lor_contributor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mdl_lor_game`
--
ALTER TABLE `mdl_lor_game`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cid` (`cid`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `mdl_lor_grade`
--
ALTER TABLE `mdl_lor_grade`
  ADD PRIMARY KEY (`grade`);

--
-- Indexes for table `mdl_lor_keyword`
--
ALTER TABLE `mdl_lor_keyword`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `mdl_lor_platform`
--
ALTER TABLE `mdl_lor_platform`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mdl_lor_type`
--
ALTER TABLE `mdl_lor_type`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mdl_lor_category`
--
ALTER TABLE `mdl_lor_category`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `mdl_lor_content`
--
ALTER TABLE `mdl_lor_content`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=418;
--
-- AUTO_INCREMENT for table `mdl_lor_contributor`
--
ALTER TABLE `mdl_lor_contributor`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `mdl_lor_game`
--
ALTER TABLE `mdl_lor_game`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;
--
-- AUTO_INCREMENT for table `mdl_lor_platform`
--
ALTER TABLE `mdl_lor_platform`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `mdl_lor_type`
--
ALTER TABLE `mdl_lor_type`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `mdl_lor_content`
--
ALTER TABLE `mdl_lor_content`
  ADD CONSTRAINT `mdl_lor_content_ibfk_1` FOREIGN KEY (`platform`) REFERENCES `mdl_lor_platform` (`id`),
  ADD CONSTRAINT `mdl_lor_content_ibfk_2` FOREIGN KEY (`type`) REFERENCES `mdl_lor_type` (`id`);

--
-- Constraints for table `mdl_lor_content_categories`
--
ALTER TABLE `mdl_lor_content_categories`
  ADD CONSTRAINT `mdl_lor_content_categories_ibfk_1` FOREIGN KEY (`content`) REFERENCES `mdl_lor_content` (`id`),
  ADD CONSTRAINT `mdl_lor_content_categories_ibfk_2` FOREIGN KEY (`category`) REFERENCES `mdl_lor_category` (`id`);

--
-- Constraints for table `mdl_lor_content_contributors`
--
ALTER TABLE `mdl_lor_content_contributors`
  ADD CONSTRAINT `mdl_lor_content_contributors_ibfk_1` FOREIGN KEY (`content`) REFERENCES `mdl_lor_content` (`id`),
  ADD CONSTRAINT `mdl_lor_content_contributors_ibfk_2` FOREIGN KEY (`contributor`) REFERENCES `mdl_lor_contributor` (`id`);

--
-- Constraints for table `mdl_lor_content_grades`
--
ALTER TABLE `mdl_lor_content_grades`
  ADD CONSTRAINT `mdl_lor_content_grades_ibfk_1` FOREIGN KEY (`content`) REFERENCES `mdl_lor_content` (`id`),
  ADD CONSTRAINT `mdl_lor_content_grades_ibfk_2` FOREIGN KEY (`grade`) REFERENCES `mdl_lor_grade` (`grade`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
