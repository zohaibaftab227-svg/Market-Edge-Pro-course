-- =============================================
-- Market Edge Pro - Complete Database Schema
-- Run this in phpMyAdmin or MySQL CLI
-- =============================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `marketedgepro` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `marketedgepro`;

-- admins table
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin: username=admin | password=admin123
INSERT INTO `admins` (`username`, `password`, `full_name`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Market Edge Pro Admin')
ON DUPLICATE KEY UPDATE username=username;

-- students table
CREATE TABLE IF NOT EXISTS `students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- courses table
CREATE TABLE IF NOT EXISTS `courses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `original_price` decimal(10,2) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `level` varchar(50) DEFAULT 'Beginner',
  `icon` varchar(100) DEFAULT 'fas fa-graduation-cap',
  `instructor` varchar(100) DEFAULT 'Market Edge Pro Team',
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- lessons table
CREATE TABLE IF NOT EXISTS `lessons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `course_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `video_url` varchar(255) NOT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `order_num` int(11) DEFAULT 0,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `lessons_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- enrollments table
CREATE TABLE IF NOT EXISTS `enrollments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `course_id` int(11) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `student_email` varchar(100) NOT NULL,
  `student_phone` varchar(20) NOT NULL,
  `student_city` varchar(100) NOT NULL,
  `education` varchar(100) DEFAULT NULL,
  `experience` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT 'Bank Transfer',
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- blog_posts table
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `author` varchar(100) DEFAULT 'Market Edge Pro Team',
  `is_published` tinyint(1) DEFAULT 1,
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- contacts table
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =============================================
-- COURSES SAMPLE DATA
-- =============================================
INSERT INTO `courses` (`title`,`category`,`description`,`price`,`original_price`,`duration`,`level`,`icon`,`instructor`,`is_featured`,`is_active`) VALUES
('Digital Marketing','Marketing','Master the art of Digital Marketing with our comprehensive course. Learn SEO, Social Media, Email Marketing, PPC, Content Marketing and more. This course is designed for beginners and professionals who want to take their marketing skills to the next level.',15000.00,25000.00,'3 Months','Beginner','fas fa-bullhorn','Market Edge Pro Team',1,1),
('Social Media Marketing (SMM)','Social Media','Learn how to grow businesses on social media platforms. Master Facebook, Instagram, TikTok, LinkedIn, and Twitter marketing. Create viral content, run ads, and build engaged communities.',12000.00,20000.00,'2 Months','Beginner','fas fa-share-alt','Market Edge Pro Team',1,1),
('Search Engine Optimization (SEO)','SEO','Dominate Google search results with our advanced SEO course. Learn On-Page SEO, Off-Page SEO, Technical SEO, Keyword Research, Link Building, and Local SEO strategies.',10000.00,18000.00,'2 Months','Intermediate','fas fa-search','Market Edge Pro Team',1,1),
('Agentic AI','Artificial Intelligence','Explore the cutting-edge world of Agentic AI. Learn how to build, deploy and manage AI agents that can autonomously perform complex tasks. Covers n8n, Make.com, AutoGPT, LangChain and more.',20000.00,35000.00,'3 Months','Advanced','fas fa-robot','Market Edge Pro Team',1,1),
('Web Development','Development','Build professional websites and web applications from scratch. Learn HTML, CSS, JavaScript, PHP, MySQL, and modern frameworks. Create dynamic and responsive websites for clients.',18000.00,30000.00,'4 Months','Beginner','fas fa-code','Market Edge Pro Team',0,1),
('Meta & Google Ads','Paid Advertising','Master paid advertising on Meta (Facebook & Instagram) and Google Ads. Learn campaign setup, targeting, optimization, retargeting, and scaling strategies to maximize ROI.',14000.00,22000.00,'2 Months','Intermediate','fas fa-ad','Market Edge Pro Team',1,1),
('Content Designing','Design','Create stunning visual content for social media, websites, and marketing materials. Master Canva, Adobe Photoshop, Illustrator, and video editing tools. Build a freelancing portfolio.',8000.00,15000.00,'2 Months','Beginner','fas fa-palette','Market Edge Pro Team',0,1),
('Artificial Intelligence','Artificial Intelligence','Dive into the world of Artificial Intelligence and Machine Learning. Learn Python for AI, Neural Networks, NLP, Computer Vision, and how to apply AI in business and marketing contexts.',22000.00,38000.00,'4 Months','Advanced','fas fa-brain','Market Edge Pro Team',0,1)
ON DUPLICATE KEY UPDATE title=title;

-- =============================================
-- BLOG POSTS SAMPLE DATA
-- =============================================
INSERT INTO `blog_posts` (`title`,`slug`,`excerpt`,`content`,`category`,`author`,`is_published`) VALUES
('10 Digital Marketing Trends to Watch in 2025','digital-marketing-trends-2025','Discover the top digital marketing trends shaping businesses in 2025. From AI personalization to short-form video, stay ahead of the curve.','<p>The digital marketing landscape is evolving faster than ever. Here are the top trends every marketer must know in 2025.</p><h3>1. AI-Powered Personalization</h3><p>Artificial Intelligence is revolutionizing how brands interact with customers. AI tools now analyze customer behavior in real-time to deliver hyper-personalized content, emails, and ads that convert at much higher rates than generic campaigns.</p><h3>2. Short-Form Video Dominance</h3><p>TikTok, Instagram Reels, and YouTube Shorts continue to dominate. Brands investing in short-form video content see 3x higher engagement rates compared to static posts. If you are not creating short videos, you are leaving money on the table.</p><h3>3. Voice Search Optimization</h3><p>With smart speakers becoming common in homes, optimizing for voice search is no longer optional. Focus on conversational keywords and featured snippets to capture voice search traffic.</p><h3>4. Social Commerce Growth</h3><p>Instagram Shopping, Facebook Marketplace, and TikTok Shop are transforming how consumers discover and buy products directly through social media platforms.</p><h3>5. Influencer Marketing Evolution</h3><p>Micro-influencers (10K-100K followers) outperform mega-influencers in engagement. Authentic, niche influencers drive better ROI for brands of all sizes.</p>','Digital Marketing','Market Edge Pro Team',1),
('How to Run Successful Facebook Ads in Pakistan','facebook-ads-pakistan-guide','Learn how to run profitable Facebook ad campaigns targeting Pakistani audiences with proven strategies and budget tips.','<p>Facebook advertising in Pakistan requires a unique approach. With over 45 million Facebook users in Pakistan, the opportunity is massive but requires smart strategy.</p><h3>Understanding Pakistani Audience</h3><p>Pakistani audiences respond best to content in Urdu or mixed Urdu-English. Emotional storytelling, family values, and aspirational content perform exceptionally well. Always test both languages to see what works for your specific audience.</p><h3>Budget Strategy for Pakistan</h3><p>You can start Facebook ads in Pakistan with as little as 500 PKR per day. Focus on cost-per-result optimization and start with traffic campaigns before moving to conversion campaigns. Scale what works and cut what does not.</p><h3>Best Ad Formats for Pakistan</h3><p>Video ads get 60% more engagement than image ads in Pakistan. Carousel ads work well for showcasing multiple products or course features. Single image ads with strong copy also perform well when done right.</p><h3>Targeting Tips</h3><p>Use detailed targeting to reach specific demographics. Interest-based targeting works well in Pakistan. Lookalike audiences can help you scale winning campaigns by finding people similar to your best customers.</p>','Social Media Marketing','Market Edge Pro Team',1),
('SEO Guide for Pakistani Businesses: Rank on Google','seo-guide-pakistani-businesses','A complete SEO guide for Pakistani businesses to rank higher on Google and drive organic traffic with proven local SEO strategies.','<p>Getting your Pakistani business to rank on Google requires a localized SEO strategy. Here is your complete guide to dominating local search results.</p><h3>Local SEO Fundamentals</h3><p>Start with Google My Business. Claim and optimize your GMB listing with accurate business information, photos, and regular posts. This is the fastest way to appear in local search results for your area.</p><h3>Keyword Research for Pakistan</h3><p>Use Google Keyword Planner and SEMrush to find keywords Pakistani users are searching. Include Urdu transliterations and city-specific keywords like digital marketing course Lahore or SEO expert Karachi.</p><h3>Technical SEO Essentials</h3><p>Ensure your website loads in under 3 seconds. Pakistani internet speeds vary, so optimize images, use caching, and consider a CDN for better performance across the country.</p><h3>Content Strategy</h3><p>Create content that answers questions your Pakistani audience is asking. Blog posts, FAQs, and how-to guides targeting local queries drive consistent organic traffic over time.</p>','SEO','Market Edge Pro Team',1),
('Why Agentic AI is the Future of Business Automation','agentic-ai-business-automation','Discover how Agentic AI is revolutionizing business automation and why learning it now gives you a massive competitive advantage.','<p>Agentic AI is transforming how businesses operate. Unlike traditional AI that responds to prompts, Agentic AI can autonomously plan, execute, and complete complex multi-step tasks without constant human intervention.</p><h3>What is Agentic AI?</h3><p>Agentic AI refers to AI systems that can take actions, use tools, and complete goals with minimal human oversight. Tools like AutoGPT, CrewAI, and n8n-based agents are leading this revolution in business automation.</p><h3>Real Business Applications</h3><p>Businesses are using Agentic AI for lead generation, customer support automation, social media posting, email campaigns, data analysis, and market research - all running autonomously 24/7 without human involvement.</p><h3>Pakistani Business Opportunity</h3><p>Early adopters in Pakistan who master Agentic AI will have a massive competitive advantage. The technology is still emerging, making this the perfect time to learn and offer these services to businesses.</p>','Artificial Intelligence','Market Edge Pro Team',1),
('Complete Guide to Content Design for Social Media','content-design-social-media-guide','Master the art of content design for social media with proven principles of visual hierarchy, color psychology, and typography.','<p>Creating scroll-stopping content is both an art and a science. Here is your complete guide to designing content that gets attention and drives results on social media platforms.</p><h3>Understanding Visual Hierarchy</h3><p>Every piece of content needs a clear visual hierarchy. The most important element should be the largest and most prominent. Use size, color, and contrast to guide the viewers eye through your design in the order you want.</p><h3>Color Psychology in Marketing</h3><p>Colors evoke powerful emotions. Red creates urgency and excitement, blue builds trust and credibility, green suggests growth and nature, and gold communicates luxury and premium quality. Choose your brand colors strategically and use them consistently.</p><h3>Typography That Converts</h3><p>Use a maximum of 2-3 fonts in your designs. Pair a bold display font with a clean body font. Always ensure readability on mobile screens where most social media content is consumed these days.</p>','Content Design','Market Edge Pro Team',1)
ON DUPLICATE KEY UPDATE title=title;

-- =============================================
-- ADMIN LOGIN CREDENTIALS
-- Username: admin
-- Password: admin123
-- =============================================
