const Footer = () => {
    return (
        <footer>
            <div className="sticky top-[100vh] flex items-center justify-between p-6">
                <p className="text-center dark:text-white-dark ltr:sm:text-left rtl:sm:text-right">
                    Copyright &copy; {new Date().getFullYear()}. Space-O
                    Technologies. All Rights Reserved.
                </p>
            </div>
        </footer>
    );
};

export default Footer;
