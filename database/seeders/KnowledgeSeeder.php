<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KnowledgeCategory;
use App\Models\KnowledgeArticle;
use App\Models\User;
use Illuminate\Support\Str;
use Carbon\Carbon;

class KnowledgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin user or create a default one
        $admin = User::first();
        
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now()
            ]);
        }
        
        // Categories
        $softwareCategory = KnowledgeCategory::create([
            'name' => 'Software',
            'slug' => 'software',
            'description' => 'Learn about different software applications, troubleshooting, and optimization techniques',
            'icon' => 'laptop-code',
            'order' => 1
        ]);
        
        $hardwareCategory = KnowledgeCategory::create([
            'name' => 'Hardware',
            'slug' => 'hardware',
            'description' => 'Find solutions for hardware issues, maintenance tips, and upgrade guides',
            'icon' => 'microchip',
            'order' => 2
        ]);
        
        $networkingCategory = KnowledgeCategory::create([
            'name' => 'Networking',
            'slug' => 'networking',
            'description' => 'Solve networking problems, configure connections, and optimize network performance',
            'icon' => 'network-wired',
            'order' => 3
        ]);
        
        $accountCategory = KnowledgeCategory::create([
            'name' => 'Accounts & Security',
            'slug' => 'accounts-security',
            'description' => 'Access your account, change settings, and learn about security best practices',
            'icon' => 'user-shield',
            'order' => 4
        ]);
        
        $dataStorageCategory = KnowledgeCategory::create([
            'name' => 'Data Storage',
            'slug' => 'data-storage',
            'description' => 'Manage your data and files, backup solutions, and data recovery guides',
            'icon' => 'database',
            'order' => 5
        ]);
        
        // Software Category Articles
        $this->createArticle(
            'Updating Software on Your Device',
            'Learn how to keep your software up-to-date to ensure security and optimal performance',
            $softwareCategory->id,
            $admin->id,
            '<h2>Why Keep Your Software Updated?</h2>
            <p>Regular software updates are crucial for:</p>
            <ul>
                <li>Patching security vulnerabilities</li>
                <li>Fixing bugs and issues</li>
                <li>Adding new features and improvements</li>
                <li>Ensuring compatibility with other applications</li>
            </ul>
            
            <h2>Checking for Updates</h2>
            <p>Most software applications have built-in update mechanisms. Here\'s how to check for updates on different systems:</p>
            
            <h3>Windows</h3>
            <ol>
                <li>Go to Settings > Update & Security > Windows Update</li>
                <li>Click "Check for updates"</li>
                <li>Install any available updates</li>
            </ol>
            
            <h3>macOS</h3>
            <ol>
                <li>Click the Apple menu > System Preferences</li>
                <li>Click "Software Update"</li>
                <li>Install any available updates</li>
            </ol>
            
            <h3>Mobile Devices</h3>
            <ol>
                <li>Android: Go to Settings > System > System update</li>
                <li>iOS: Go to Settings > General > Software Update</li>
            </ol>
            
            <h2>Best Practices</h2>
            <ul>
                <li>Schedule regular updates during non-working hours</li>
                <li>Always back up your data before major updates</li>
                <li>Check for application updates separately from system updates</li>
                <li>Consider enabling automatic updates for security patches</li>
            </ul>',
            true,
            Carbon::now()->subDays(15),
            100,
            ['updates', 'software', 'maintenance']
        );
        
        $this->createArticle(
            'Latest Features in Software Suite X',
            'Discover the new capabilities and improvements in the latest version of Software Suite X',
            $softwareCategory->id,
            $admin->id,
            '<h2>Introducing Software Suite X v3.0</h2>
            <p>The newest version of Software Suite X brings significant improvements and exciting new features to enhance your productivity.</p>
            
            <h2>Key New Features</h2>
            <h3>Streamlined Interface</h3>
            <p>The user interface has been completely redesigned for improved navigation and accessibility. The new design allows for quicker access to commonly used features and a more intuitive workflow.</p>
            
            <h3>AI-Powered Assistance</h3>
            <p>Version 3.0 introduces an AI assistant that can help with:</p>
            <ul>
                <li>Automating repetitive tasks</li>
                <li>Suggesting improvements to your work</li>
                <li>Providing context-sensitive help</li>
                <li>Learning from your usage patterns to better assist you</li>
            </ul>
            
            <h3>Cloud Integration</h3>
            <p>Enhanced cloud capabilities allow for seamless synchronization across devices and improved collaboration with team members. Your work is automatically saved and accessible from any device.</p>
            
            <h3>Performance Improvements</h3>
            <ul>
                <li>50% faster startup time</li>
                <li>Reduced memory usage</li>
                <li>Optimized rendering for complex projects</li>
                <li>Background processing for large files</li>
            </ul>
            
            <h2>How to Upgrade</h2>
            <p>Existing users can upgrade through the in-app update system or by downloading the latest version from our website. All your settings and projects will be preserved during the upgrade process.</p>',
            true,
            Carbon::now()->subDays(2),
            45,
            ['software suite', 'features', 'update']
        );
        
        // Hardware Category Articles
        $this->createArticle(
            'Troubleshooting Common Hardware Issues',
            'Solutions for typical hardware problems you might encounter with your devices',
            $hardwareCategory->id,
            $admin->id,
            '<h2>Device Won\'t Power On</h2>
            <ol>
                <li>Check power connections and cables</li>
                <li>Try a different power outlet</li>
                <li>For laptops, remove the battery and power adapter, hold the power button for 30 seconds, then reconnect</li>
                <li>Check for physical damage to power components</li>
            </ol>
            
            <h2>Overheating</h2>
            <p>If your device is running hot or shutting down due to heat:</p>
            <ol>
                <li>Clean dust from vents and fans</li>
                <li>Ensure proper ventilation around the device</li>
                <li>Check that all cooling fans are working properly</li>
                <li>Consider using a cooling pad for laptops</li>
                <li>Update BIOS and firmware</li>
            </ol>
            
            <h2>Strange Noises</h2>
            <p>Different noises can indicate specific problems:</p>
            <ul>
                <li><strong>Clicking from hard drive:</strong> Back up data immediately and replace the drive</li>
                <li><strong>Grinding fans:</strong> Clean or replace the affected fan</li>
                <li><strong>Coil whine:</strong> Often normal but can indicate power supply issues</li>
                <li><strong>Beeping during startup:</strong> Check your motherboard manual for beep code meanings</li>
            </ul>
            
            <h2>Peripheral Connection Problems</h2>
            <ol>
                <li>Try different USB ports or cables</li>
                <li>Restart the device after connecting peripherals</li>
                <li>Update or reinstall device drivers</li>
                <li>Check for OS updates that might address connectivity issues</li>
            </ol>
            
            <h2>Display Issues</h2>
            <ol>
                <li>Check cable connections</li>
                <li>Test with an alternative monitor if possible</li>
                <li>Update graphics drivers</li>
                <li>Adjust resolution settings</li>
                <li>For laptops, try connecting an external monitor</li>
            </ol>',
            true,
            Carbon::now()->subDays(30),
            150,
            ['hardware', 'troubleshooting', 'repair']
        );
        
        $this->createArticle(
            'New Hardware Guide for Model Y',
            'Setup instructions and optimization tips for the latest Model Y hardware',
            $hardwareCategory->id,
            $admin->id,
            '<h2>Model Y Specifications</h2>
            <p>The new Model Y features significant hardware improvements:</p>
            <ul>
                <li>Next-generation processor (50% faster than previous model)</li>
                <li>Enhanced graphics processing unit</li>
                <li>Increased memory capacity and speed</li>
                <li>Improved cooling system for sustained performance</li>
                <li>Extended battery life (up to 12 hours)</li>
            </ul>
            
            <h2>Initial Setup</h2>
            <ol>
                <li>Remove all packaging and inspect for any damage</li>
                <li>Connect the power adapter and charge fully before first use</li>
                <li>Power on and follow the on-screen setup instructions</li>
                <li>Connect to your network to download the latest firmware</li>
                <li>Configure user accounts and security settings</li>
            </ol>
            
            <h2>Optimizing Performance</h2>
            <h3>Recommended Settings</h3>
            <ul>
                <li>Enable performance mode for demanding applications</li>
                <li>Set display refresh rate to 120Hz for smoother experience</li>
                <li>Configure thermal profiles based on your workload</li>
                <li>Adjust power settings to balance performance and battery life</li>
            </ul>
            
            <h3>Software Recommendations</h3>
            <p>Install these applications to maximize your Model Y experience:</p>
            <ul>
                <li>System Monitor Pro - for performance tracking</li>
                <li>Driver Update Utility - to keep drivers current</li>
                <li>Model Y Control Center - for advanced hardware configuration</li>
            </ul>
            
            <h2>Troubleshooting</h2>
            <p>If you encounter issues with your new Model Y, try these steps:</p>
            <ol>
                <li>Ensure all firmware and drivers are up to date</li>
                <li>Perform a system reset if experiencing persistent problems</li>
                <li>Check our online diagnostic tool for hardware-specific solutions</li>
                <li>Contact technical support with your device serial number</li>
            </ol>',
            true,
            Carbon::now()->subDays(5),
            65,
            ['hardware', 'model Y', 'setup guide']
        );
        
        // Networking Category Articles
        $this->createArticle(
            'Troubleshooting Network Connectivity',
            'Steps to diagnose and fix common network connection problems',
            $networkingCategory->id,
            $admin->id,
            '<h2>Basic Network Troubleshooting</h2>
            <ol>
                <li><strong>Restart networking equipment:</strong> Turn off your modem and router, wait 30 seconds, then turn them back on</li>
                <li><strong>Check physical connections:</strong> Ensure all cables are properly connected</li>
                <li><strong>Verify Wi-Fi settings:</strong> Confirm you\'re connecting to the correct network with the right password</li>
                <li><strong>Test multiple devices:</strong> Determine if the issue affects one or all devices</li>
            </ol>
            
            <h2>Common Issues and Solutions</h2>
            
            <h3>Slow Internet Connection</h3>
            <ol>
                <li>Run a speed test at <a href="https://www.speedtest.net" target="_blank">speedtest.net</a></li>
                <li>Check for bandwidth-intensive applications running in the background</li>
                <li>Position your router in a central location away from interference</li>
                <li>Consider updating your router firmware</li>
                <li>Contact your ISP if speeds are consistently below your plan\'s rate</li>
            </ol>
            
            <h3>Intermittent Connection Drops</h3>
            <ol>
                <li>Check for interference from other electronic devices</li>
                <li>Update network adapter drivers</li>
                <li>Try different Wi-Fi channels to avoid congestion</li>
                <li>Check for overheating network equipment</li>
            </ol>
            
            <h3>Cannot Connect to Specific Websites</h3>
            <ol>
                <li>Clear browser cache and cookies</li>
                <li>Try using a different DNS server</li>
                <li>Check if the website is down using <a href="https://downforeveryoneorjustme.com" target="_blank">Down for Everyone or Just Me</a></li>
                <li>Try accessing the site from a different browser</li>
            </ol>
            
            <h3>VPN Connection Issues</h3>
            <ol>
                <li>Verify your VPN credentials</li>
                <li>Try connecting to different VPN servers</li>
                <li>Check if your ISP is blocking VPN connections</li>
                <li>Update your VPN client software</li>
            </ol>
            
            <h2>Advanced Troubleshooting</h2>
            <ol>
                <li>Use Command Prompt/Terminal to run network diagnostics:
                    <ul>
                        <li>ipconfig /flushdns (Windows) or sudo killall -HUP mDNSResponder (Mac)</li>
                        <li>ping google.com</li>
                        <li>tracert google.com (Windows) or traceroute google.com (Mac/Linux)</li>
                    </ul>
                </li>
                <li>Check for IP address conflicts</li>
                <li>Reset network settings on your device</li>
                <li>Factory reset your router as a last resort</li>
            </ol>',
            true,
            Carbon::now()->subDays(20),
            200,
            ['networking', 'connectivity', 'troubleshooting', 'internet']
        );
        
        $this->createArticle(
            'Tips for Remote Work Networking',
            'Optimize your home network for better remote work performance',
            $networkingCategory->id,
            $admin->id,
            '<h2>Setting Up an Optimal Home Office Network</h2>
            <p>Working remotely requires a reliable and secure network connection. Follow these recommendations to optimize your home network for professional use.</p>
            
            <h2>Improving Wi-Fi Performance</h2>
            <ol>
                <li><strong>Position your router centrally:</strong> Place your router in a central location away from walls and metal objects</li>
                <li><strong>Upgrade your equipment:</strong> Consider a mesh network system for larger homes to eliminate dead zones</li>
                <li><strong>Separate 2.4GHz and 5GHz networks:</strong> Use 5GHz for video conferencing and bandwidth-intensive work</li>
                <li><strong>Update firmware:</strong> Keep your router firmware updated for best performance and security</li>
                <li><strong>Use Quality of Service (QoS):</strong> Configure QoS settings to prioritize work applications</li>
            </ol>
            
            <h2>Wired Connections</h2>
            <p>When possible, use wired Ethernet connections for:</p>
            <ul>
                <li>More stable connections during important meetings</li>
                <li>Faster file transfers and downloads</li>
                <li>Reduced latency for time-sensitive applications</li>
                <li>Consistent speeds regardless of wireless interference</li>
            </ul>
            
            <h2>Securing Your Home Network</h2>
            <ol>
                <li>Change default router login credentials</li>
                <li>Use WPA3 encryption if available, or WPA2 at minimum</li>
                <li>Enable firewall protection on both router and devices</li>
                <li>Create a separate guest network for non-work devices</li>
                <li>Consider a VPN for accessing sensitive company resources</li>
            </ol>
            
            <h2>Troubleshooting Video Conference Issues</h2>
            <ol>
                <li>Close unnecessary applications and browser tabs</li>
                <li>Ask household members to limit streaming during important calls</li>
                <li>Position yourself closer to the router</li>
                <li>Use headphones with a microphone to improve audio quality</li>
                <li>Have a mobile hotspot as backup for critical meetings</li>
            </ol>
            
            <h2>Recommended Tools for Network Monitoring</h2>
            <ul>
                <li><strong>Speedtest.net:</strong> Regular speed tests to verify connection quality</li>
                <li><strong>WiFi Analyzer:</strong> Identify channel congestion and optimize placement</li>
                <li><strong>GlassWire:</strong> Monitor bandwidth usage by application</li>
                <li><strong>Ping tools:</strong> Check connection stability throughout the workday</li>
            </ul>',
            true,
            Carbon::now()->subDays(3),
            50,
            ['networking', 'remote work', 'wifi', 'home office']
        );
        
        // Accounts & Security Category Articles
        $this->createArticle(
            'How to Secure Your Account',
            'Best practices for keeping your account safe from unauthorized access',
            $accountCategory->id,
            $admin->id,
            '<h2>Creating Strong Passwords</h2>
            <p>A strong password is your first line of defense against unauthorized access:</p>
            <ul>
                <li>Use at least 12 characters</li>
                <li>Include uppercase and lowercase letters, numbers, and special characters</li>
                <li>Avoid common words, phrases, or personal information</li>
                <li>Don\'t reuse passwords across different accounts</li>
                <li>Consider using a password manager to generate and store complex passwords</li>
            </ul>
            
            <h2>Enable Two-Factor Authentication (2FA)</h2>
            <p>Two-factor authentication adds an extra layer of security by requiring:</p>
            <ol>
                <li>Something you know (your password)</li>
                <li>Something you have (like your phone or a security key)</li>
            </ol>
            <p>To enable 2FA on your account:</p>
            <ol>
                <li>Go to Account Settings > Security</li>
                <li>Select "Enable Two-Factor Authentication"</li>
                <li>Choose your preferred 2FA method (authenticator app recommended)</li>
                <li>Follow the setup instructions</li>
                <li>Save your backup codes in a secure location</li>
            </ol>
            
            <h2>Monitor Account Activity</h2>
            <p>Regularly check for suspicious activity:</p>
            <ul>
                <li>Review recent login history in your account settings</li>
                <li>Enable login notifications to receive alerts for new devices</li>
                <li>Check connected applications and remove any that you don\'t recognize or no longer use</li>
                <li>Sign out of all sessions periodically, especially on shared or public computers</li>
            </ul>
            
            <h2>Keep Your Devices Secure</h2>
            <ol>
                <li>Keep operating systems and applications updated</li>
                <li>Use reputable antivirus/anti-malware software</li>
                <li>Be cautious when clicking links or downloading attachments</li>
                <li>Lock your devices when not in use</li>
                <li>Use secure, private networks when accessing sensitive information</li>
            </ol>
            
            <h2>What to Do If Your Account Is Compromised</h2>
            <ol>
                <li>Change your password immediately</li>
                <li>Enable or reset two-factor authentication</li>
                <li>Check account recovery options and ensure they\'re up to date</li>
                <li>Review account activity for unauthorized changes</li>
                <li>Contact support if you notice any suspicious transactions or if you need assistance</li>
                <li>Scan your devices for malware</li>
            </ol>',
            true,
            Carbon::now()->subDays(25),
            180,
            ['security', 'passwords', '2FA', 'account protection']
        );
        
        // Data Storage Category Articles
        $this->createArticle(
            'Best Practices for Data Backup',
            'Learn how to protect your important files with proper backup strategies',
            $dataStorageCategory->id,
            $admin->id,
            '<h2>Why Backups Are Essential</h2>
            <p>Regular backups protect your data from:</p>
            <ul>
                <li>Hardware failure</li>
                <li>Accidental deletion</li>
                <li>Ransomware and malware attacks</li>
                <li>Theft or loss of devices</li>
                <li>Natural disasters</li>
            </ul>
            
            <h2>The 3-2-1 Backup Strategy</h2>
            <p>Follow this industry-standard approach:</p>
            <ul>
                <li><strong>3</strong> - Keep at least three copies of your data</li>
                <li><strong>2</strong> - Store the copies on two different types of media</li>
                <li><strong>1</strong> - Keep one copy offsite (like cloud storage)</li>
            </ul>
            
            <h2>Backup Options</h2>
            
            <h3>External Hard Drives</h3>
            <ul>
                <li>Affordable and high capacity</li>
                <li>Fast data transfer speeds</li>
                <li>Portable options available</li>
                <li>Consider encryption for sensitive data</li>
            </ul>
            
            <h3>Network Attached Storage (NAS)</h3>
            <ul>
                <li>Centralized storage for multiple devices</li>
                <li>Often includes redundancy (RAID)</li>
                <li>Accessible from anywhere on your network</li>
                <li>Many include automatic backup features</li>
            </ul>
            
            <h3>Cloud Storage</h3>
            <ul>
                <li>Accessible from anywhere with internet</li>
                <li>Generally includes server-side redundancy</li>
                <li>Subscription-based with various capacity options</li>
                <li>Consider privacy and security features</li>
            </ul>
            
            <h2>Backup Frequency</h2>
            <p>How often you should back up depends on how frequently your data changes:</p>
            <ul>
                <li><strong>Critical business data:</strong> Daily or real-time</li>
                <li><strong>Important personal files:</strong> Weekly</li>
                <li><strong>System images:</strong> Monthly or after significant changes</li>
            </ul>
            
            <h2>Testing Your Backups</h2>
            <p>Regularly verify that your backups are working:</p>
            <ol>
                <li>Schedule periodic test restores</li>
                <li>Check for file integrity</li>
                <li>Ensure all necessary files are included</li>
                <li>Document the restore process for emergencies</li>
            </ol>
            
            <h2>Backup Software Recommendations</h2>
            <ul>
                <li><strong>Windows:</strong> File History, Windows Backup, Macrium Reflect</li>
                <li><strong>macOS:</strong> Time Machine</li>
                <li><strong>Cross-platform:</strong> Acronis True Image, Backblaze, Carbonite</li>
            </ul>',
            true,
            Carbon::now()->subDays(10),
            120,
            ['backup', 'data protection', 'storage', 'disaster recovery']
        );
    }
    
    /**
     * Helper function to create knowledge articles
     */
    private function createArticle($title, $meta_description, $category_id, $author_id, $content, $is_published, $published_at, $views_count, $tags)
    {
        $slug = Str::slug($title);
        
        // Check if slug exists
        $count = KnowledgeArticle::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
        
        return KnowledgeArticle::create([
            'title' => $title,
            'slug' => $slug,
            'meta_description' => $meta_description,
            'category_id' => $category_id,
            'author_id' => $author_id,
            'content' => $content,
            'is_published' => $is_published,
            'published_at' => $published_at,
            'views_count' => $views_count,
            'tags' => $tags
        ]);
    }
} 