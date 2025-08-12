@extends('layouts.guest')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-9">
                <div class="plate plate-padding information-page">
                    <h2>Definitions</h2>
                    <p>The following definitions apply to these terms and conditions:</p>
                    <ul>
                        <li>"{{ config('app.name') }}": {{ config('app.company') }} and all other holding or subsidiary companies.</li>
                        <li>"Website": All pages, content, databases, software, code and graphics within the {{ config('app.url') }} domain and hosting server.</li>
                        <li>"User": The person, company or organisation which is engaged in activity on the website.</li>
                    </ul>
                    <h2>Data Usage Policy</h2>
                    <p>This data usage policy governs how user information is collected, used, stored, shared and disclosed on the website.</p>
                    <ul>
                        <li>{{ config('app.name') }} may collect and store any information supplied by the user up until the user opts to delete their account.</li>
                        <li>{{ config('app.name') }} may use information supplied by the user to operate, maintain and provide website features and functionality to the user's experience.</li>
                        <li>At no point will {{ config('app.name') }} intentionally share or disclose the user's personal information, unless permitted by the user or we are required to by UK or international law.</li>
                        <li>We accept no responsibility for the theft, loss, manipulation or disclosure of the user's information {{ config('app.name') }} stores.</li>
                        <li>{{ config('app.name') }} may store cookies on the user's device and access them in order to provide certain functions or features on this website. However, for the user's safety, any sensitive information stored as a cookie is always encrypted first.</li>
                        <li>Any passwords that the user provides is immediately encrypted with a one way hashing algorithm before transfer to storage. At no point will {{ config('app.name') }} have access to original user passwords.</li>
                        <li>{{ config('app.name') }} may use the user's email addresses or phone number as a means of contact for: deadline reminders, changes to terms and conditions, changes to account data, important information updates and opportunities relevant to the user.</li>
                        <li>At any point, the user has the right to permanently delete their account and any information that they have supplied to {{ config('app.name') }}. (For information on how to do this visit the "My Account" section upon login)</li>
                    </ul>
                    <h2>Conditions of Use</h2>
                    <p>The following applies to all activity on the website.</p>
                    <ul>
                        <li>By visiting our website and/ or purchasing something from our website, you agree to be bound by all the terms and conditions found here, including those additional terms and conditions and policies referenced herein and/or available by hyperlink. These terms of Service apply to all users of the site, including without limitation users who are browsers, vendors, customers, merchants, and/ or contributors of content.</li>
                        <li>{{ config('app.name') }} does not guarantee a continuous error free, virus free, secure operation and access to this website, the user's account and any information available in connection therewith.</li>
                        <li>{{ config('app.name') }} accept no responsibility for any policies, terms or conditions for any third parties linked to within the contents of the website.</li>
                        <li>{{ config('app.name') }} reserve the right to change these terms and conditions at any point.</li>
                        <li>{{ config('app.name') }} accept no responsibility for the user's financial performance. The website is to be used as a GUIDE only and no statistics should be assumed as fact.</li>
                        <li>The website is not to be construed in any way as financial advice. It is only intended as a financial analytic tool for non-professional use.</li>
                    </ul>
                    <h2>Premium Services</h2>
                    <ul>
                        <li>Prices for {{ config('app.name') }}'s services are subject to change without notice.</li>
                        <li>{{ config('app.name') }} reserve the right at any time to modify or discontinue the service (or any part or content thereof) without notice at any time.</li>
                        <li>{{ config('app.name') }} shall not be liable to the user or to any third-party for any modification, price change, suspension or discontinuance of the service.</li>
                        <li>{{ config('app.name') }} do not warrant that the quality of any services, information, or other material purchased or obtained by the user will meet their expectations, or that any errors in the service will be corrected.</li>
                        <li>{{ config('app.name') }} reserve the right to refuse any order the user places with us.</li>
                        <li>The user agrees to provide current, complete and accurate purchase and account information for all purchases made at our store.</li>
                        <li>{{ config('app.name') }} shall have no obligation to offer the user a refund or exchange for any services or products purchased.</li>
                    </ul>
                    <h2>Disputes or Queries</h2>
                    <p>If anything in the above terms and conditions is not fully understood or you simply have a question about a specific point then please let us know by visiting the Contact Us page.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
