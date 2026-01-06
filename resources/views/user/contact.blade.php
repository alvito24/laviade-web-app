<x-layouts.app title="Contact Us">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Contact Us</h1>
            <p class="text-xl text-secondary max-w-2xl mx-auto">
                Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Contact Info -->
            <div class="lg:col-span-1">
                <div class="space-y-8">
                    <div>
                        <h3 class="font-semibold mb-2">Email</h3>
                        <p class="text-secondary">support@laviade.com</p>
                    </div>
                    <div>
                        <h3 class="font-semibold mb-2">Phone</h3>
                        <p class="text-secondary">+62 21 1234 5678</p>
                    </div>
                    <div>
                        <h3 class="font-semibold mb-2">Address</h3>
                        <p class="text-secondary">
                            Jl. Fashion Street No. 123<br>
                            Jakarta Selatan, 12345<br>
                            Indonesia
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold mb-2">Business Hours</h3>
                        <p class="text-secondary">
                            Monday - Friday: 9:00 AM - 6:00 PM<br>
                            Saturday: 10:00 AM - 4:00 PM<br>
                            Sunday: Closed
                        </p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-surface rounded-lg p-8">
                    <h2 class="text-xl font-bold mb-6">Send us a message</h2>
                    <form>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block font-medium mb-2">Name</label>
                                <input type="text" required
                                    class="w-full px-4 py-3 bg-white border border-custom rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300">
                            </div>
                            <div>
                                <label class="block font-medium mb-2">Email</label>
                                <input type="email" required
                                    class="w-full px-4 py-3 bg-white border border-custom rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium mb-2">Subject</label>
                            <input type="text" required
                                class="w-full px-4 py-3 bg-white border border-custom rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300">
                        </div>
                        <div class="mb-6">
                            <label class="block font-medium mb-2">Message</label>
                            <textarea rows="5" required
                                class="w-full px-4 py-3 bg-white border border-custom rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300"></textarea>
                        </div>
                        <button type="submit" class="btn-primary rounded-lg w-full md:w-auto">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>