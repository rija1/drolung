# Drolung Project Specialist Agents

## Agent Directory for Drolung International Foundation WordPress Project

**Location**: `/agents/` (project root level)

This directory contains specialized AI agent specifications designed specifically for the Drolung WordPress project. Each agent specification documents the expertise, tools, and knowledge areas for project-specific assistance.

**Important Note**: These are **agent specification files** (documentation) - not functional agents themselves. They serve as reference guides for understanding what each specialist should know about the Drolung project.

---

## 🎨 **Drolung Frontend Specialist**
**File**: `drolung-frontend-specialist.md`
**Tools**: Read, Write, Edit, Grep, Glob, Bash

### When to Use:
- CSS styling and design modifications
- Responsive layout issues
- Template visual improvements
- Mobile navigation problems
- Animation and interaction enhancements
- Browser compatibility fixes
- Accessibility improvements
- Performance optimization (frontend)

### Expertise:
- WordPress template system
- CSS Grid/Flexbox layouts
- Buddhist charity design aesthetics
- Mobile-first responsive design
- Cross-browser compatibility
- Frontend performance optimization

---

## ⚙️ **Drolung Backend Specialist**
**File**: `drolung-backend-specialist.md`
**Tools**: Read, Write, Edit, Grep, Glob, Bash

### When to Use:
- PHP function development
- ACF field group modifications
- WordPress hooks and filters
- Custom functionality implementation
- Plugin integration
- Security enhancements
- Backend performance optimization
- WordPress API integrations

### Expertise:
- WordPress development standards
- ACF programmatic registration
- PHP best practices
- Security implementation
- Database query optimization
- WordPress template hierarchy

---

## 🗄️ **Drolung Database Specialist**
**File**: `drolung-database-specialist.md`
**Tools**: Read, Write, Grep, Glob, Bash

### When to Use:
- Database structure analysis
- ACF field data management
- Multisite database issues
- Performance query optimization
- Data migration/import/export
- Backup and recovery procedures
- Database security audits
- Storage optimization

### Expertise:
- WordPress database schema
- ACF meta storage patterns
- Multisite architecture
- MySQL optimization
- Data integrity management
- Performance monitoring

---

## 🧪 **Drolung Testing Specialist**
**File**: `drolung-testing-specialist.md`
**Tools**: Read, Write, Bash

### When to Use:
- Quality assurance testing
- Responsive design validation
- Browser compatibility testing
- ACF functionality verification
- Performance testing
- Accessibility audits
- User experience validation
- Regression testing

### Expertise:
- Functional testing methodologies
- Cross-device testing
- WordPress testing standards
- Performance benchmarking
- Accessibility compliance
- User acceptance testing

---

## 🐛 **Drolung Debugging Specialist**
**File**: `drolung-debugging-specialist.md`
**Tools**: Read, Grep, Bash

### When to Use:
- PHP error diagnosis
- ACF field troubleshooting
- Template loading issues
- JavaScript/jQuery problems
- Performance bottlenecks
- WordPress configuration issues
- Plugin conflicts
- Multisite debugging

### Expertise:
- WordPress debugging tools
- Error log analysis
- ACF troubleshooting
- Template hierarchy issues
- Performance profiling
- Security debugging

---

## 🚀 **Usage Instructions**

### How to Invoke Agents:
```
Use the Task tool with these agent types:
- frontend-specialist: For UI/UX and styling issues
- backend-specialist: For PHP and WordPress development
- database-specialist: For data and database concerns
- testing-specialist: For quality assurance
- debugging-specialist: For troubleshooting problems
```

### Agent Selection Guide:
- **Visual/CSS Issues** → Frontend Specialist
- **Functionality/PHP** → Backend Specialist  
- **Data/Performance** → Database Specialist
- **Quality Assurance** → Testing Specialist
- **Problem Solving** → Debugging Specialist

### Best Practices:
1. **Be Specific**: Provide detailed context about the issue
2. **Include Environment**: Mention browser, device, or setup details
3. **Share Error Messages**: Include any console or PHP errors
4. **Describe Expected Behavior**: What should happen vs what actually happens
5. **Previous Steps**: Mention any troubleshooting already attempted

---

## 🔧 **Project Context for All Agents**

### Theme Structure:
- **Location**: `/wp-content/themes/drolung-theme/`
- **Key Files**: functions.php, front-page.php, page.php, style.css
- **Assets**: `/assets/images/`, `/assets/js/main.js`

### Technology Stack:
- **WordPress**: Multisite installation
- **ACF Plugin**: Advanced Custom Fields for content management
- **Database**: Local MySQL (drolung.local)
- **Fonts**: Google Fonts (Cormorant Garamond, Inter, Lato)
- **Framework**: Vanilla CSS/JS (no frameworks)

### Color Scheme:
- Primary: #1a3d5e (dark blue)
- Secondary: #d4a574 (gold)
- Accent: #8b6f47 (brown)
- Light: #f8f5f0 (cream)

### Key Features:
- 8 ACF field groups for homepage content
- Responsive card-based layout
- Buddhist charity aesthetic
- Mobile-first design approach
- Performance optimized assets

---

## 📞 **Agent Coordination**

When complex issues span multiple domains:
1. **Start with Debugging** to identify root cause
2. **Consult appropriate specialist** based on diagnosis
3. **Use Testing** to verify solutions
4. **Consider cross-domain impact** on other areas

Each agent is designed to work independently but can reference solutions from other specialists when needed.