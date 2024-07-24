@extends('layouts.auth-app')
@push('css')
<style>
    h6, h3{
        font-weight: 700;
    }
</style>
@endpush
@section('content')
<div class="w-100 d-flex align-items-center justify-content-center my-3">
    <img src="{{assets('assets/images/logo.svg')}}" alt="" width="120">
</div>
<div>
    <div class="text-center my-4" style="color: #1079c0;">
        <h3>Privacy & Policy</h3>
    </div>
    <div class="container my-5">
        <p>This Privacy Policy describes how Journey with Journals LLC Application ("we", "us", or "our") collects, uses, and discloses information that we obtain from users of Journey with Journals LLC service (the "Service").</p>

        <h6>1. Information We Collect</h6>
        <p>Personal Information: When users sign up for an account, we collect personal information such as name, email address, and payment information. Journal Entries: We collect and store journal entries and any metadata associated with them, including timestamps and location data if provided. Log Data: When users access the Service, we collect log data, which may include IP addresses, browser type, pages visited, and timestamps.
        </p>

        <h6>2. Use of Information</h6>
        <p>We use the information collected to:
        Provide, maintain, and improve the Service.
        Personalize user experiences.
        Communicate with users, including responding to inquiries and providing support.
        Analyze usage trends and improve the Service.
        </p>

        <h6>3. Data Sharing and Disclosure</h6>
        <p>We do not sell, trade, or otherwise transfer user information to third parties without user consent, except as required by law.
        We may share information with service providers who help us deliver the Service, subject to confidentiality agreements.
        </p>

        <h6>4. Data Security</h6>
        <p>We implement security measures to protect user information against unauthorized access or alteration.
        However, no method of transmission over the internet or electronic storage is completely secure, so we cannot guarantee absolute security.
        </p>

        <h6>5. Data Breach Notification</h6>
        <p>In the event of a data breach that compromises user information security, we will notify affected users promptly and take necessary steps to mitigate any potential harm.</p>

        <h6>6. Data Retention</h6>
        <p>We will retain user information as long as necessary to provide the Service and fulfill the purposes outlined in this Privacy Policy.
        Users can request the deletion of their account and associated information by contacting us.
        </p>

        <h6>7. Data Transfer</h6>
        <p>By using our Service, you understand and agree that your information may be transferred to and stored on servers located in different geographic regions.</p>

        <h6>8. Data Controller and Data Processor</h6>
        <p>For users in regions where data protection laws require us to designate a data controller and data processor, please note that Journey with Journals LLC is the data controller for the personal information collected through the Service. We act as the data processor for the processing of user data on behalf of our users.</p>

        <h6>9. Data Portability</h6>
        <p>Users have the right to request a copy of their personal information in a structured, commonly used, and machine-readable format. Users can make such requests by contacting us using the information provided in this Privacy Policy.</p>

        <h6>10. Contact Information for Data Protection Concerns</h6>
        <p>If you have any concerns about how your personal information is being handled, or if you have questions about this Privacy Policy, please contact our Data Protection Officer at concerns@journeywithjournals.com.</p>

        <h6>11. User Rights</h6>
        <p>Users have the right to:
        Access, correct, or delete their personal information.
        Opt-out of marketing communications.
        </p>

        <h6>12. Cookies and Tracking Technologies</h6>
        <p>We use cookies and similar tracking technologies to enhance user experience, track user interactions with the Service, and collect information for analytics and marketing purposes. Users can manage cookie preferences through their browser settings.</p>

        <h6>13. Third-Party Services</h6>
        <p>Our Service may contain links to third-party websites or services. This Privacy Policy applies only to our Service, and we are not responsible for the privacy practices of third parties.
        Journey with Journals LLC application may incorporate third-party services, such as analytics providers or social media platforms. These third parties may collect information about your interactions with the Service through cookies or other tracking technologies.
        We encourage you to review the privacy policies of these third-party services to understand how your information is managed by them.
        </p>

        <h6>14. Children's Privacy</h6>
        <p>Our Service is not intended for individuals under the age of 13. We do not knowingly collect personal information from children under 13. If you are a parent or guardian and believe we have collected information from a child, please contact us immediately. If you are a parent or guardian and consent to a child under 13 to utilize this application, you accept responsibility on their behalf.</p>

        <h6>15. International Users</h6>
        <p>By using the Service, you acknowledge and agree that your information may be transferred outside your country of residence.
        We will handle your information in accordance with this Privacy Policy regardless of where the data is processed.
        </p>

        <h6>16. Consent</h6>
        <p>By using our Service, you consent to the collection, use, and sharing of your information as described in this Privacy Policy.</p>

        <h6>17. Governing Law</h6>
        <p>This Privacy Policy is governed by and construed in accordance with the laws of Missouri, USA., without regard to its conflict of law principles.</p>

        <h6>18. User Responsibilities</h6>
        <p>Users are responsible for maintaining the confidentiality of their account credentials and must notify us immediately of any unauthorized access to their account. Users are responsible for a secured password to their account and should not store their password for automatic sign in. Users are responsible for changing their password as often as they see fit.</p>

        <h6>19. User Feedback and Reviews</h6>
        <p>From time to time, we may request feedback or reviews from users to improve our Service. Participation in these surveys or reviews is voluntary.</p>

        <h6>20. Contact Information</h6>
        <p>If you have any questions, concerns, or feedback regarding our Privacy Policy or data practices, please contact us at concerns@journeywithjournals.com.</p>

        <h6>21. California Residents - California Consumer Privacy Act (CCPA) Rights</h6>
        <p>The California Consumer Privacy Act (CCPA) provides residents of California with certain rights regarding their personal information. If you are a California resident, you have the right to request access to your personal information, request deletion of your personal information, and opt-out of the sale of your personal information, if applicable. To exercise your CCPA rights, please contact us using the information provided in this Privacy Policy.</p>

        <h6>22. European Economic Area (EEA) Residents - General Data Protection Regulation (GDPR) Compliance</h6>
        <p>We are committed to complying with the General Data Protection Regulation (GDPR) for users in the European Economic Area (EEA). If you are an EEA resident, you have certain rights regarding your personal information, including the right to access, correct, or delete your data.
        To exercise your GDPR rights, please contact us using the information provided in this Privacy Policy.
        </p>

        <h6>23. Entire Agreement</h6>
        <p>This Privacy Policy constitutes the entire agreement between the users and Journey with Journals LLC Application regarding the collection and use of personal information through the Service.</p>

        <h6>24. Opt-Out Mechanism</h6>
        <p>Users have the option to opt-out of certain data collection or processing activities by contacting us using the information provided in this Privacy Policy. Please note that opting out of certain data processing activities may impact your ability to use certain features of the Service.</p>

        <h6>25. Severability</h6>
        <p>If any provision of this Privacy Policy is found to be unlawful, void, or unenforceable, that provision shall be deemed severable from the remaining provisions and shall not affect the validity and enforceability of the remaining provisions.</p>

        <h6>26. Relationship of Parties</h6>
        <p>Nothing in this Privacy Policy creates a partnership, joint venture, agency, or employment relationship between the users and Journey with Journals LLC Application.</p>

        <h6>27. Survival</h6>
        <p>The obligations and liabilities contained in this Privacy Policy shall survive the termination of the user's use of the Service.</p>
        
        <h6>28. Language</h6>
        <p>This Privacy Policy is written in English. In case of any discrepancies or conflicts between translations, the English version of this Privacy Policy shall prevail.</p>

        <h6>29. Changes to the Privacy Policy</h6>
        <p>We reserve the right to modify or update this Privacy Policy at any time. Any changes will be effective immediately upon posting the revised Privacy Policy on our website and in the Privacy Policy section of the application.
        We encourage users to review this Privacy Policy periodically to stay informed about how we are protecting and managing user information.
        </p>

        <h6>30. User Acknowledgment</h6>
        <p>By using Journey with Journals LLC application, you acknowledge that you have read and understood this Privacy Policy and agree to be bound by its terms and conditions.
        By using Journey with Journals LLC application, you consent to the terms outlined in this Privacy Policy, including the collection, use, and sharing of your information as described herein.
        </p>

        <h6>31. Effective Date of Privacy Policy</h6>
        <p>This Privacy Policy is effective as of July 10, 2024. Your continued use of our Service after the Effective Date constitutes acceptance of this Privacy Policy.</p>

        <h6>32. Contact Us</h6>
        <p>If you have any questions or concerns about this Privacy Policy, please contact us at concerns@journeywithjournals.com</p>
    </div>
</div>
@endsection